@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Here is your financial overview.')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <x-stat-card :label="$stat['label']" :value="$stat['value']" :meta="$stat['change']" :tone="$stat['tone']" />
        @endforeach
    </div>

    <div class="mt-5 grid gap-5 xl:grid-cols-5">
        <section class="panel p-5 sm:p-6 xl:col-span-3">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-[18px] font-semibold text-fg">Income vs. Expenses</h2>
                    <p class="mt-1 text-[12px] text-muted">Monthly overview</p>
                </div>
                <span class="chip chip-muted">{{ $months[0]['label'] }} – {{ $months[count($months) - 1]['label'] }} {{ now()->year }}</span>
            </div>
            <div class="mt-5">
                <x-bar-chart :months="$months" :height="210" />
            </div>
        </section>

        <section class="panel p-5 sm:p-6 xl:col-span-2">
            <h2 class="text-[18px] font-semibold text-fg">Spending by Category</h2>
            <p class="mt-1 text-[12px] text-muted">{{ now()->format('F Y') }}</p>
            <div class="mt-7">
                <x-donut-chart :categories="$categories" :value="'₱'.number_format($expenseTotal, 2)" label="Expenses" />
            </div>
        </section>
    </div>

    <section class="panel mt-5 overflow-hidden">
        <div class="flex items-center justify-between gap-4 px-5 pt-5 sm:px-6 sm:pt-6">
            <div>
                <h2 class="text-[18px] font-semibold text-fg">Recent Transactions</h2>
                <p class="mt-1 text-[12px] text-muted">Your latest money movements</p>
            </div>
            <a href="{{ route('transactions') }}" class="text-[12px] font-semibold text-primary-soft hover:text-fg">View all →</a>
        </div>

        <div class="scroll-thin mt-4 overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left">
                <thead>
                    <tr>
                        <th class="table-head px-6 py-3">Date</th>
                        <th class="table-head px-4 py-3">Description</th>
                        <th class="table-head px-4 py-3">Category</th>
                        <th class="table-head px-4 py-3">Type</th>
                        <th class="table-head px-4 py-3">Amount</th>
                        <th class="table-head px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent as $transaction)
                        <tr class="table-row">
                            <td class="whitespace-nowrap px-6 py-4 text-[12px] text-fg">{{ $transaction->date->format('M d, Y') }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-[12px] text-fg">{{ $transaction->description }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-[12px] text-muted">{{ $transaction->category }}</td>
                            <td class="whitespace-nowrap px-4 py-4">
                                <span class="chip {{ $transaction->type === 'Income' ? 'chip-income' : 'chip-expense' }}">{{ $transaction->type }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-[12px] font-medium {{ $transaction->type === 'Income' ? 'text-success' : 'text-fg' }}">
                                {{ $transaction->type === 'Income' ? '+' : '−' }}₱{{ number_format((float) $transaction->amount, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('transactions.edit', $transaction) }}" class="inline-flex items-center gap-1.5 text-[12px] text-muted hover:text-primary-soft">
                                    <x-icon name="edit" :size="13" /> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-[12px] text-muted">No transactions yet. Add your first one to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
