<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SummarizesFinances;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    use SummarizesFinances;

    /**
     * Display the financial report for the current month.
     */
    public function index(): View
    {
        $from = now()->startOfMonth();
        $to = now()->endOfMonth();
        $totals = $this->periodTotals($from, $to);
        $netBalance = $totals['income'] - $totals['expenses'];

        return view('reports', [
            ...$this->shared(),
            'stats' => [
                ['label' => 'TOTAL INCOME', 'value' => $this->money($totals['income']), 'tone' => 'success'],
                ['label' => 'TOTAL EXPENSES', 'value' => $this->money($totals['expenses']), 'tone' => 'danger'],
                ['label' => 'NET BALANCE', 'value' => $this->money($netBalance), 'tone' => 'primary'],
            ],
            'months' => $this->monthlyTotals(),
            'categories' => $this->categoryBreakdown($from, $to),
            'expenseTotal' => $totals['expenses'],
            'summary' => [
                ['label' => 'Income', 'value' => $this->money($totals['income'])],
                ['label' => 'Expenses', 'value' => $this->money($totals['expenses'])],
                ['label' => 'Net Balance', 'value' => $this->money($netBalance)],
            ],
            'period' => $from->format('M j, Y').' – '.$to->format('M j, Y'),
        ]);
    }
}
