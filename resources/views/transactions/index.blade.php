@extends('layouts.app')

@section('title', 'Transactions')
@section('subtitle', 'Track and manage your income and expenses.')

@section('content')
    <section class="panel p-4 sm:p-5">
        <form action="{{ route('transactions') }}" method="GET" class="flex flex-col gap-3 xl:flex-row xl:items-center">
            <label class="relative min-w-0 flex-1 xl:max-w-[300px]">
                <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-muted">
                    <x-icon name="search" :size="15" />
                </span>
                <input
                    name="q"
                    type="search"
                    class="field pl-10"
                    placeholder="Search transactions..."
                    aria-label="Search transactions"
                    value="{{ $filters['q'] ?? '' }}"
                >
            </label>

            <div class="grid gap-3 sm:grid-cols-3 xl:ml-auto xl:w-auto xl:min-w-[520px] xl:grid-cols-3">
                <select name="type" class="field" aria-label="Filter by type">
                    <option value="">All Types</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}" @selected(($filters['type'] ?? '') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="category" class="field" aria-label="Filter by category">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                <input name="from" type="date" class="field" aria-label="Date from" value="{{ $filters['from'] ?? '' }}">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-secondary h-10 px-4 text-[12px]">Filter</button>
                @if (array_filter($filters))
                    <a href="{{ route('transactions') }}" class="btn btn-secondary h-10 px-4 text-[12px]">Reset</a>
                @endif
            </div>
        </form>
    </section>

    <section class="panel mt-5 overflow-hidden">
        <div class="flex items-center justify-between gap-4 px-5 pt-5 sm:px-6">
            <div>
                <h2 class="text-[18px] font-semibold text-fg">All transactions</h2>
                <p class="mt-1 text-[12px] text-muted"><span>{{ $transactions->total() }}</span> {{ $transactions->total() === 1 ? 'record' : 'records' }} found</p>
            </div>
            <button type="button" data-modal-open="add-transaction-modal" class="btn btn-primary h-10 px-4 text-[12px]">
                <x-icon name="plus" :size="14" /> Add Transaction
            </button>
        </div>

        <div class="scroll-thin mt-4 overflow-x-auto">
            <table class="w-full min-w-[900px] border-collapse text-left">
                <thead>
                    <tr>
                        <th class="table-head px-6 py-3">Date</th>
                        <th class="table-head px-4 py-3">Description</th>
                        <th class="table-head px-4 py-3">Category</th>
                        <th class="table-head px-4 py-3">Type</th>
                        <th class="table-head px-4 py-3">Amount</th>
                        <th class="table-head px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="table-row" data-transaction-row>
                            <td class="whitespace-nowrap px-6 py-[15px] text-[12px] text-fg">{{ $transaction->date->format('M d, Y') }}</td>
                            <td class="whitespace-nowrap px-4 py-[15px] text-[12px] text-fg">{{ $transaction->description }}</td>
                            <td class="whitespace-nowrap px-4 py-[15px] text-[12px] text-muted">{{ $transaction->category }}</td>
                            <td class="whitespace-nowrap px-4 py-[15px]">
                                <span class="chip {{ $transaction->type === 'Income' ? 'chip-income' : 'chip-expense' }}">{{ $transaction->type }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-[15px] text-[12px] font-medium {{ $transaction->type === 'Income' ? 'text-success' : 'text-fg' }}">
                                {{ $transaction->type === 'Income' ? '+' : '−' }}₱{{ number_format((float) $transaction->amount, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-[15px] text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('transactions.edit', $transaction) }}" class="rounded-lg p-2 text-muted transition hover:bg-white/5 hover:text-primary-soft" aria-label="Edit {{ $transaction->description }}">
                                        <x-icon name="edit" :size="14" />
                                    </a>
                                    <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Delete this transaction?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-muted transition hover:bg-white/5 hover:text-danger" aria-label="Delete {{ $transaction->description }}">
                                            <x-icon name="trash" :size="14" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[13px] text-muted">No transactions match your filters yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-line-soft px-5 py-4 text-[12px] text-muted sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p>
                Showing <span class="text-fg">{{ $transactions->firstItem() ?? 0 }}–{{ $transactions->lastItem() ?? 0 }}</span>
                of {{ $transactions->total() }} transactions
            </p>
            @if ($transactions->hasPages())
                <div class="flex items-center gap-1.5">
                    @if ($transactions->onFirstPage())
                        <span class="flex size-8 items-center justify-center rounded-lg border border-line text-muted/50">
                            <x-icon name="arrow-left" :size="14" />
                        </span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="flex size-8 items-center justify-center rounded-lg border border-line text-muted hover:text-fg" aria-label="Previous page">
                            <x-icon name="arrow-left" :size="14" />
                        </a>
                    @endif

                    <span class="flex size-8 items-center justify-center rounded-lg border border-primary bg-primary/15 text-[12px] font-semibold text-fg">{{ $transactions->currentPage() }}</span>
                    <span class="px-1 text-[11px]">of {{ $transactions->lastPage() }}</span>

                    @if ($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="flex size-8 items-center justify-center rounded-lg border border-line text-muted hover:text-fg" aria-label="Next page">
                            <x-icon name="arrow-right" :size="14" />
                        </a>
                    @else
                        <span class="flex size-8 items-center justify-center rounded-lg border border-line text-muted/50">
                            <x-icon name="arrow-right" :size="14" />
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <div class="mt-5 flex justify-end">
        <button type="button" data-modal-open="add-transaction-modal" class="btn btn-primary">
            <x-icon name="plus" :size="15" /> Add Transaction
        </button>
    </div>
@endsection
