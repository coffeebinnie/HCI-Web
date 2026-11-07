@extends('layouts.app')

@section('title', 'Edit transaction')
@section('subtitle', 'Update the details for this transaction.')

@section('content')
    <section class="panel p-5 sm:p-7">
        <div>
            <h2 class="text-[18px] font-semibold text-fg">Transaction details</h2>
            <p class="mt-1.5 text-[12px] text-muted">Edit the record below, then save your changes.</p>
        </div>

        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="mt-6">
            @csrf
            @method('PUT')

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="transaction-name" class="label">Description</label>
                    <input
                        id="transaction-name"
                        name="description"
                        type="text"
                        class="field"
                        value="{{ old('description', $transaction->description) }}"
                        required
                    >
                </div>

                <div>
                    <label for="transaction-amount" class="label">Amount (₱)</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-muted">₱</span>
                        <input
                            id="transaction-amount"
                            name="amount"
                            type="number"
                            min="0.01"
                            step="0.01"
                            class="field pl-7"
                            value="{{ old('amount', number_format((float) $transaction->amount, 2, '.', '')) }}"
                            required
                        >
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <span class="label">Type</span>
                    <div class="flex flex-col gap-3 sm:flex-row" data-type-group="#edit-transaction-type">
                        @php($currentType = old('type', $transaction->type))
                        <button
                            type="button"
                            data-type="Expense"
                            aria-pressed="{{ $currentType === 'Expense' ? 'true' : 'false' }}"
                            class="flex h-[44px] flex-1 items-center justify-center gap-2 rounded-[10px] border text-[13px] font-medium transition {{ $currentType === 'Expense' ? 'border-primary bg-primary/15 text-fg' : 'border-line text-muted' }}"
                        >
                            <span data-dot>{{ $currentType === 'Expense' ? '◉' : '○' }}</span> Expense
                        </button>
                        <button
                            type="button"
                            data-type="Income"
                            aria-pressed="{{ $currentType === 'Income' ? 'true' : 'false' }}"
                            class="flex h-[44px] flex-1 items-center justify-center gap-2 rounded-[10px] border text-[13px] font-medium transition {{ $currentType === 'Income' ? 'border-primary bg-primary/15 text-fg' : 'border-line text-muted' }}"
                        >
                            <span data-dot>{{ $currentType === 'Income' ? '◉' : '○' }}</span> Income
                        </button>
                    </div>
                    <input id="edit-transaction-type" type="hidden" name="type" value="{{ $currentType }}">
                </div>

                <div>
                    <label for="transaction-category" class="label">Category</label>
                    <select id="transaction-category" name="category" class="field" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(old('category', $transaction->category) === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="transaction-date" class="label">Date</label>
                    <input id="transaction-date" name="date" type="date" class="field" value="{{ old('date', $transaction->date->toDateString()) }}" required>
                </div>

                <div>
                    <label for="transaction-method" class="label">Payment Method</label>
                    <select id="transaction-method" name="method" class="field">
                        <option value="">Select method</option>
                        @foreach ($methods as $method)
                            <option value="{{ $method }}" @selected(old('method', $transaction->method) === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="transaction-notes" class="label">Notes</label>
                    <textarea
                        id="transaction-notes"
                        name="notes"
                        rows="3"
                        class="field h-auto resize-none py-3"
                        placeholder="Add a short note"
                    >{{ old('notes', $transaction->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-7 border-t border-line-soft pt-5">
                <h3 class="text-[15px] font-semibold text-fg">Activity</h3>
                <p class="mt-2 text-[12px] text-muted">Created {{ $transaction->created_at->format('M d, Y g:i A') }}</p>
                <p class="mt-1 text-[12px] text-muted">Last edited {{ $transaction->updated_at->format('M d, Y g:i A') }}</p>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                <button
                    type="submit"
                    form="delete-transaction-form"
                    class="btn btn-secondary text-danger sm:min-w-[130px]"
                    onclick="return confirm('Delete this transaction?')"
                >
                    Delete
                </button>
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('transactions') }}" class="btn btn-secondary sm:min-w-[130px]">Cancel</a>
                    <button type="submit" class="btn btn-primary sm:min-w-[160px]">Save changes</button>
                </div>
            </div>
        </form>

        <form id="delete-transaction-form" action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </section>
@endsection
