<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSavingsGoalRequest;
use App\Http\Requests\UpdateSavingsGoalRequest;
use App\Models\Contribution;
use App\Models\SavingsGoal;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavingsGoalController extends Controller
{
    /**
     * Display savings goals and their contributions.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $goals = SavingsGoal::query()
            ->withSum('contributions', 'amount')
            ->when($filters['q'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('target_date')
            ->orderBy('name')
            ->get();

        $totalSaved = $goals->sum(fn (SavingsGoal $goal): float => $goal->savedAmount());
        $completed = $goals->filter(
            fn (SavingsGoal $goal): bool => $goal->savedAmount() >= (float) $goal->target_amount
        )->count();

        $contributionsThisMonth = (float) Contribution::query()
            ->whereBetween('contributed_on', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $contributions = Contribution::query()
            ->with('savingsGoal')
            ->orderByDesc('contributed_on')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('savings.index', [
            ...$this->shared(),
            'goals' => $goals,
            'filters' => $filters,
            'contributions' => $contributions,
            'total' => $totalSaved,
            'completed' => $completed,
            'contributionsThisMonth' => $contributionsThisMonth,
            'sources' => Contribution::SOURCES,
        ]);
    }

    /**
     * Show the standalone contribution form.
     */
    public function contribution(): View
    {
        $goal = SavingsGoal::query()
            ->withSum('contributions', 'amount')
            ->orderBy('id')
            ->first();

        return view('savings.contribution', [
            ...$this->shared(),
            'goal' => $goal,
            'goals' => SavingsGoal::query()->orderBy('name')->get(),
            'sources' => Contribution::SOURCES,
        ]);
    }

    /**
     * Store a newly created savings goal.
     */
    public function store(StoreSavingsGoalRequest $request): RedirectResponse
    {
        SavingsGoal::create($request->validated());

        return redirect()
            ->route('savings')
            ->with('status', 'Savings goal created successfully.');
    }

    /**
     * Update the given savings goal.
     */
    public function update(UpdateSavingsGoalRequest $request, SavingsGoal $savingsGoal): RedirectResponse
    {
        $savingsGoal->update($request->validated());

        return redirect()
            ->route('savings')
            ->with('status', 'Savings goal updated successfully.');
    }

    /**
     * Delete the given savings goal and its contributions.
     */
    public function destroy(SavingsGoal $savingsGoal): RedirectResponse
    {
        DB::transaction(function () use ($savingsGoal): void {
            Transaction::query()
                ->whereIn('contribution_id', $savingsGoal->contributions()->select('id'))
                ->delete();

            $savingsGoal->delete();
        });

        return redirect()
            ->route('savings')
            ->with('status', 'Savings goal deleted successfully.');
    }
}
