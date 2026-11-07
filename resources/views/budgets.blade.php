@extends('layouts.app')

@section('title', 'Budgets')
@section('subtitle', 'Plan your spending and stay within your limits.')

@section('content')
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ($stats as $stat)
            <x-stat-card :label="$stat['label']" :value="$stat['value']" :meta="$stat['meta']" tone="muted" />
        @endforeach
    </div>

    <div class="mt-7 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-[18px] font-semibold text-fg">Category Budgets</h2>
            <p class="mt-1 text-[12px] text-muted">{{ $budgets->count() }} {{ $budgets->count() === 1 ? 'budget' : 'budgets' }} across all months</p>
        </div>
        <button type="button" data-modal-open="add-budget-modal" data-budget-add class="btn btn-primary h-10 px-4 text-[12px]">
            <x-icon name="plus" :size="14" /> Add Budget
        </button>
    </div>

    <div class="mt-4 grid gap-5 xl:grid-cols-2">
        @forelse ($budgets as $budget)
            @php($percent = $budget->progressPercent())
            <section class="panel p-5 sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-[18px] font-semibold text-fg">{{ $budget->category }}</h3>
                        <p class="mt-1 text-[11px] text-muted">{{ $budget->periodLabel() }} · Monthly budget</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button
                            type="button"
                            data-modal-open="add-budget-modal"
                            data-budget-edit
                            data-action="{{ route('budgets.update', $budget) }}"
                            data-category="{{ $budget->category }}"
                            data-limit="{{ number_format((float) $budget->monthly_limit, 2, '.', '') }}"
                            data-month="{{ $budget->month }}"
                            data-year="{{ $budget->year }}"
                            class="rounded-lg border border-line p-2 text-muted transition hover:border-primary/40 hover:text-primary-soft"
                            aria-label="Edit {{ $budget->category }} budget"
                        >
                            <x-icon name="edit" :size="14" />
                        </button>
                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" onsubmit="return confirm('Delete this budget?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-line p-2 text-muted transition hover:border-danger/40 hover:text-danger" aria-label="Delete {{ $budget->category }} budget">
                                <x-icon name="trash" :size="14" />
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-5 flex items-end justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.09em] text-muted">Spent</p>
                        <p class="mt-1 text-[20px] font-bold text-fg">₱{{ number_format((float) $budget->spent, 2) }}</p>
                    </div>
                    <span class="chip {{ $percent >= 85 ? 'chip-expense' : 'chip-muted' }}">{{ $percent }}% used</span>
                </div>

                <div class="mt-4">
                    <div class="progress-track">
                        <div class="progress-bar" style="width: {{ $percent }}%"></div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between gap-4 text-[12px] text-muted">
                    <span>Budget limit: <span class="text-fg">₱{{ number_format((float) $budget->monthly_limit, 2) }}</span></span>
                    <span>Remaining: <span class="text-fg">₱{{ number_format(max(0, (float) $budget->monthly_limit - (float) $budget->spent), 2) }}</span></span>
                </div>
            </section>
        @empty
            <section class="panel p-8 text-center text-[13px] text-muted xl:col-span-2">
                @if (! empty($filters['q'] ?? null))
                    No budgets match your search.
                @else
                    No budgets yet. Add your first category budget to start tracking.
                @endif
            </section>
        @endforelse
    </div>

    <div class="mt-5 flex justify-end">
        <button type="button" data-modal-open="add-budget-modal" data-budget-add class="btn btn-primary">
            <x-icon name="plus" :size="15" /> Add Budget
        </button>
    </div>
@endsection

@push('modals')
    <div id="add-budget-modal" data-modal class="modal-backdrop hidden">
        <div class="modal-panel max-w-[450px] p-6 sm:p-7" role="dialog" aria-modal="true" aria-labelledby="add-budget-title">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="add-budget-title" class="text-[22px] font-bold tracking-[-0.01em] text-fg">Add Budget</h2>
                    <p id="budget-modal-description" class="mt-1 text-[12px] text-muted">Set a monthly spending limit per category.</p>
                </div>
                <button type="button" data-modal-close class="rounded-lg p-1.5 text-muted transition hover:bg-white/5 hover:text-fg" aria-label="Close dialog">
                    <x-icon name="close" :size="18" />
                </button>
            </div>

            <form
                id="budget-form"
                action="{{ route('budgets.store') }}"
                method="POST"
                data-store-url="{{ route('budgets.store') }}"
                class="mt-6 space-y-4"
            >
                @csrf
                <input id="budget-method" type="hidden" name="_method" value="POST">

                <div>
                    <label for="budget-category" class="label">Category</label>
                    <select id="budget-category" name="category" class="field" required>
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="budget-limit" class="label">Monthly Budget Limit</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-muted">₱</span>
                        <input id="budget-limit" name="monthly_limit" type="number" min="0.01" step="0.01" class="field pl-7" placeholder="0.00" value="{{ old('monthly_limit') }}" required>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="budget-month" class="label">Month</label>
                        <select id="budget-month" name="month" class="field">
                            @foreach ($months as $month)
                                <option value="{{ $month['value'] }}" @selected((int) old('month', now()->month) === $month['value'])>{{ $month['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="budget-year" class="label">Year</label>
                        <select id="budget-year" name="year" class="field">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" @selected((int) old('year', now()->year) === $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" data-modal-close class="btn btn-secondary min-w-[110px]">Cancel</button>
                    <button type="submit" id="budget-submit" class="btn btn-primary min-w-[130px]">Save Budget</button>
                </div>
            </form>
        </div>
    </div>
@endpush
