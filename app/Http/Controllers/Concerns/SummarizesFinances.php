<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Transaction;
use Carbon\CarbonInterface;

trait SummarizesFinances
{
    /**
     * Totals for income and expenses over a period.
     *
     * @return array{income: float, expenses: float}
     */
    protected function periodTotals(CarbonInterface $from, CarbonInterface $to): array
    {
        $totals = Transaction::query()
            ->whereBetween('date', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->get(['type', 'amount'])
            ->groupBy('type')
            ->map(fn ($group) => (float) $group->sum('amount'));

        return [
            'income' => $totals->get('Income', 0.0),
            'expenses' => $totals->get('Expense', 0.0),
        ];
    }

    /**
     * Income and expense totals grouped by month.
     *
     * @return list<array{label: string, income: float, expense: float}>
     */
    protected function monthlyTotals(int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        $transactions = Transaction::query()
            ->where('date', '>=', $start)
            ->get(['date', 'type', 'amount']);

        $totals = [];

        for ($offset = 0; $offset < $months; $offset++) {
            $month = $start->copy()->addMonths($offset);
            $key = $month->format('Y-m');

            $monthly = $transactions->filter(
                fn (Transaction $transaction): bool => $transaction->date->format('Y-m') === $key
            );

            $totals[] = [
                'label' => $month->format('M'),
                'income' => (float) $monthly->where('type', 'Income')->sum('amount'),
                'expense' => (float) $monthly->where('type', 'Expense')->sum('amount'),
            ];
        }

        return $totals;
    }

    /**
     * Spending grouped by category for the donut chart.
     *
     * @return list<array{label: string, percent: int, color: string}>
     */
    protected function categoryBreakdown(CarbonInterface $from, CarbonInterface $to): array
    {
        $totals = Transaction::query()
            ->where('type', 'Expense')
            ->whereBetween('date', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->get(['category', 'amount'])
            ->groupBy('category')
            ->map(fn ($group) => (float) $group->sum('amount'))
            ->sortDesc();

        $grandTotal = $totals->sum();

        if ($grandTotal <= 0) {
            return [];
        }

        $top = $totals->take(4);

        if ($totals->count() > 4) {
            $top->put('Other', $totals->slice(4)->sum());
        }

        $palette = ['#8b5cf6', '#a78bfa', '#34d399', '#fbbf24', '#f87171'];
        $categories = [];
        $allocated = 0;
        $index = 0;
        $count = $top->count();

        foreach ($top as $label => $amount) {
            $isLast = $index === $count - 1;
            $percent = $isLast
                ? max(0, 100 - $allocated)
                : (int) round($amount / $grandTotal * 100);

            $allocated += $percent;

            $categories[] = [
                'label' => $label,
                'percent' => $percent,
                'color' => $palette[$index] ?? '#94a3b8',
            ];

            $index++;
        }

        return $categories;
    }
}
