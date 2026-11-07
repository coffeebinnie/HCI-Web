{{-- Shared add-transaction modal --}}
<div id="add-transaction-modal" data-modal class="modal-backdrop hidden">
    <div class="modal-panel max-w-[470px] p-6 sm:p-7" role="dialog" aria-modal="true" aria-labelledby="add-transaction-title">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 id="add-transaction-title" class="text-[22px] font-bold tracking-[-0.01em] text-fg">Add Transaction</h2>
                <p class="mt-1 text-[12px] text-muted">Record a new income or expense.</p>
            </div>
            <button type="button" data-modal-close class="rounded-lg p-1.5 text-muted transition hover:bg-white/5 hover:text-fg" aria-label="Close dialog">
                <x-icon name="close" :size="18" />
            </button>
        </div>

        <form action="{{ route('transactions.store') }}" method="POST" class="mt-6 space-y-4">
            @csrf

            <div>
                <span class="label">Transaction Type</span>
                <div class="flex gap-3" data-type-group="#add-transaction-type">
                    <button type="button" data-type="Expense" aria-pressed="true" class="flex h-[40px] flex-1 items-center justify-center gap-2 rounded-[10px] border border-primary bg-primary/15 text-[13px] font-medium text-fg">
                        <span data-dot>◉</span> Expense
                    </button>
                    <button type="button" data-type="Income" aria-pressed="false" class="flex h-[40px] flex-1 items-center justify-center gap-2 rounded-[10px] border border-line text-[13px] font-medium text-muted">
                        <span data-dot>○</span> Income
                    </button>
                </div>
                <input id="add-transaction-type" type="hidden" name="type" value="{{ old('type', 'Expense') }}">
            </div>

            <div>
                <label for="add-description" class="label">Description</label>
                <input id="add-description" name="description" type="text" class="field" placeholder="e.g. Grocery shopping" value="{{ old('description') }}" required>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="add-category" class="label">Category</label>
                    <select id="add-category" name="category" class="field" required>
                        <option value="">Select category</option>
                        @foreach (\App\Models\Transaction::CATEGORIES as $category)
                            <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="add-amount" class="label">Amount</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-muted">₱</span>
                        <input id="add-amount" name="amount" type="number" min="0.01" step="0.01" class="field pl-7" placeholder="0.00" value="{{ old('amount') }}" required>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="add-date" class="label">Date</label>
                    <input id="add-date" name="date" type="date" class="field" value="{{ old('date', now()->toDateString()) }}" required>
                </div>
                <div>
                    <label for="add-method" class="label">Payment Method</label>
                    <select id="add-method" name="method" class="field">
                        <option value="">Select method</option>
                        @foreach (\App\Models\Transaction::METHODS as $method)
                            <option value="{{ $method }}" @selected(old('method') === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="add-notes" class="label">Notes (optional)</label>
                <input id="add-notes" name="notes" type="text" class="field" placeholder="Add a short note" value="{{ old('notes') }}">
            </div>

            <div class="flex items-center justify-end gap-3 pt-3">
                <button type="button" data-modal-close class="btn btn-secondary min-w-[110px]">Cancel</button>
                <button type="submit" class="btn btn-primary min-w-[140px]">Save Transaction</button>
            </div>
        </form>
    </div>
</div>
