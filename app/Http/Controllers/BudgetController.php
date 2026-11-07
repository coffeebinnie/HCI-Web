<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BudgetController extends Controller
{
    /**
     * Display a listing of budgets with their actual spending.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $budgets = Budget::query()
            ->when($filters['q'] ?? null, fn ($query, string $search) => $query->where('category', 'like', "%{$search}%"))
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->orderBy('category')
            ->get();

        $spending = Transaction::query()
            ->where('type', 'Expense')
            ->get(['category', 'date', 'amount'])
            ->groupBy(fn (Transaction $transaction): string => $transaction->category.'|'.$transaction->date->format('Y-m'))
            ->map(fn ($group): float => (float) $group->sum('amount'));

        $budgets->each(function (Budget $budget) use ($spending): void {
            $key = $budget->category.'|'.Carbon::create($budget->year, $budget->month, 1)->format('Y-m');
            $budget->spent = $spending->get($key, 0.0);
        });

        $totalBudget = (float) $budgets->sum('monthly_limit');
        $totalSpent = (float) $budgets->sum('spent');

        return view('budgets', [
            ...$this->shared(),
            'budgets' => $budgets,
            'filters' => $filters,
            'stats' => [
                ['label' => 'TOTAL BUDGET', 'value' => $this->money($totalBudget), 'meta' => 'All saved limits'],
                ['label' => 'TOTAL SPENT', 'value' => $this->money($totalSpent), 'meta' => 'Against saved limits'],
                ['label' => 'REMAINING', 'value' => $this->money(max(0, $totalBudget - $totalSpent)), 'meta' => 'Available to spend'],
            ],
            'categories' => Transaction::CATEGORIES,
            'months' => collect(range(1, 12))->map(fn (int $month): array => [
                'value' => $month,
                'label' => Carbon::createFromDate(now()->year, $month, 1)->format('F'),
            ])->all(),
            'years' => range(now()->year - 2, now()->year + 2),
        ]);
    }

    /**
     * Store a newly created budget.
     */
    public function store(StoreBudgetRequest $request): RedirectResponse
    {
        Budget::create($request->validated());

        return redirect()
            ->route('budgets')
            ->with('status', 'Budget added successfully.');
    }

    /**
     * Update the given budget.
     */
    public function update(UpdateBudgetRequest $request, Budget $budget): RedirectResponse
    {
        $budget->update($request->validated());

        return redirect()
            ->route('budgets')
            ->with('status', 'Budget updated successfully.');
    }

    /**
     * Delete the given budget.
     */
    public function destroy(Budget $budget): RedirectResponse
    {
        $budget->delete();

        return redirect()
            ->route('budgets')
            ->with('status', 'Budget deleted successfully.');
    }
}
