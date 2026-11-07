<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SummarizesFinances;
use App\Models\Budget;
use App\Models\Contribution;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    use SummarizesFinances;

    /**
     * Display the financial overview.
     */
    public function index(): View
    {
        $from = now()->startOfMonth();
        $to = now()->endOfMonth();
        $totals = $this->periodTotals($from, $to);

        $budgetLimit = (float) Budget::query()
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->sum('monthly_limit');

        $totalSavings = (float) Contribution::query()->sum('amount');
        $remainingBudget = max(0, $budgetLimit - $totals['expenses']);

        return view('dashboard', [
            ...$this->shared(),
            'stats' => [
                ['label' => 'TOTAL INCOME', 'value' => $this->money($totals['income']), 'change' => now()->format('F Y'), 'tone' => 'success'],
                ['label' => 'TOTAL EXPENSES', 'value' => $this->money($totals['expenses']), 'change' => now()->format('F Y'), 'tone' => 'danger'],
                ['label' => 'REMAINING BUDGET', 'value' => $this->money($remainingBudget), 'change' => 'Against this month\'s limits', 'tone' => 'primary'],
                ['label' => 'TOTAL SAVINGS', 'value' => $this->money($totalSavings), 'change' => 'Across all goals', 'tone' => 'success'],
            ],
            'months' => $this->monthlyTotals(),
            'categories' => $this->categoryBreakdown($from, $to),
            'expenseTotal' => $totals['expenses'],
            'recent' => Transaction::query()
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->limit(5)
                ->get(),
        ]);
    }
}
