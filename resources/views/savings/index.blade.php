@extends('layouts.app')

@section('title', 'Savings Goals')
@section('subtitle', 'Small steps today, bigger goals tomorrow.')

@section('content')
    <div class="flex flex-col gap-5 lg:flex-row lg:items-stretch">
        <section class="panel flex-1 p-6">
            <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-muted">Total Savings</p>
            <p class="mt-3 text-[34px] font-bold leading-none tracking-[-0.02em] text-fg">₱{{ number_format($total, 2) }}</p>
            <p class="mt-3 text-[12px] text-muted">Across all {{ $goals->count() }} {{ $goals->count() === 1 ? 'goal' : 'goals' }}</p>

            <div class="mt-6 grid grid-cols-3 gap-3">
                @foreach ([
                    ['label' => 'Goals', 'value' => (string) $goals->count()],
                    ['label' => 'Completed', 'value' => (string) $completed],
                    ['label' => 'This month', 'value' => '₱'.number_format($contributionsThisMonth, 2)],
                ] as $item)
                    <div class="rounded-xl border border-line bg-ink-950/40 px-3 py-3">
                        <p class="text-[10px] uppercase tracking-[0.08em] text-muted">{{ $item['label'] }}</p>
                        <p class="mt-1.5 text-[14px] font-semibold text-fg">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="panel flex flex-col justify-between gap-5 p-6 lg:w-[360px]">
            <div>
                <span class="flex size-11 items-center justify-center rounded-xl border border-primary/25 bg-primary/10 text-primary-soft">
                    <x-icon name="sparkle" :size="20" />
                </span>
                <h2 class="mt-4 text-[17px] font-semibold text-fg">Keep the momentum going</h2>
                <p class="mt-2 text-[12px] leading-relaxed text-muted">Small, consistent contributions add up. Add a little to a goal today.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button" data-modal-open="add-contribution-modal" data-contribution-add class="btn btn-primary flex-1" @disabled($goals->isEmpty())>Add Contribution</button>
                <button type="button" data-modal-open="add-goal-modal" data-goal-add class="btn btn-secondary flex-1">New Goal</button>
            </div>
        </section>
    </div>

    <div class="mt-7 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-[18px] font-semibold text-fg">Your Goals</h2>
            <p class="mt-1 text-[12px] text-muted">Track progress toward what matters.</p>
        </div>
        <button type="button" data-modal-open="add-goal-modal" data-goal-add class="btn btn-primary h-10 px-4 text-[12px]">
            <x-icon name="plus" :size="14" /> Add Savings Goal
        </button>
    </div>

    <div class="mt-4 space-y-4">
        @forelse ($goals as $goal)
            @php
                $saved = $goal->savedAmount();
                $percent = $goal->progressPercent();
            @endphp
            <section class="panel p-5 sm:p-6">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    <div class="flex min-w-0 flex-1 items-center gap-4">
                        <span class="flex size-[58px] shrink-0 items-center justify-center rounded-2xl border border-primary/20 bg-primary/10 text-[24px] font-bold text-primary-soft">◈</span>
                        <div class="min-w-0">
                            <h3 class="truncate text-[18px] font-semibold text-fg">{{ $goal->name }}</h3>
                            <p class="mt-1 text-[12px] text-muted">₱{{ number_format($saved, 2) }} saved of ₱{{ number_format((float) $goal->target_amount, 2) }}</p>
                            @if ($goal->description)
                                <p class="mt-1 truncate text-[11px] text-muted">{{ $goal->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4 sm:w-[320px]">
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[11px] text-muted">Progress</span>
                                <span class="text-[14px] font-semibold text-fg">{{ $percent }}%</span>
                            </div>
                            <div class="progress-track mt-2">
                                <div class="progress-bar" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 sm:justify-end">
                        <p class="text-[11px] text-muted">{{ $goal->target_date ? 'Target: '.$goal->target_date->format('M d, Y') : 'No target date' }}</p>
                        <div class="flex items-center gap-1.5">
                            <button
                                type="button"
                                data-modal-open="add-contribution-modal"
                                data-contribution-add
                                data-goal-id="{{ $goal->id }}"
                                class="btn btn-secondary h-9 px-3.5 text-[11px]"
                            >
                                <x-icon name="plus" :size="13" /> Add Contribution
                            </button>
                            <button
                                type="button"
                                data-modal-open="add-goal-modal"
                                data-goal-edit
                                data-action="{{ route('savings-goals.update', $goal) }}"
                                data-name="{{ $goal->name }}"
                                data-target="{{ number_format((float) $goal->target_amount, 2, '.', '') }}"
                                data-target-date="{{ $goal->target_date?->toDateString() }}"
                                data-description="{{ $goal->description }}"
                                class="rounded-lg border border-line p-2 text-muted transition hover:border-primary/40 hover:text-primary-soft"
                                aria-label="Edit {{ $goal->name }}"
                            >
                                <x-icon name="edit" :size="14" />
                            </button>
                            <form action="{{ route('savings-goals.destroy', $goal) }}" method="POST" onsubmit="return confirm('Delete this goal and all of its contributions?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-line p-2 text-muted transition hover:border-danger/40 hover:text-danger" aria-label="Delete {{ $goal->name }}">
                                    <x-icon name="trash" :size="14" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        @empty
            <section class="panel p-8 text-center text-[13px] text-muted">
                @if (! empty($filters['q'] ?? null))
                    No savings goals match your search.
                @else
                    No savings goals yet. Create your first goal to start saving.
                @endif
            </section>
        @endforelse
    </div>

    <div class="mt-5 flex justify-end">
        <button type="button" data-modal-open="add-goal-modal" data-goal-add class="btn btn-primary">
            <x-icon name="plus" :size="15" /> Add Savings Goal
        </button>
    </div>

    <section class="panel mt-7 overflow-hidden">
        <div class="px-5 pt-5 sm:px-6">
            <h2 class="text-[18px] font-semibold text-fg">Recent Contributions</h2>
            <p class="mt-1 text-[12px] text-muted">The latest money added to your goals.</p>
        </div>

        <div class="scroll-thin mt-4 overflow-x-auto">
            <table class="w-full min-w-[760px] border-collapse text-left">
                <thead>
                    <tr>
                        <th class="table-head px-6 py-3">Date</th>
                        <th class="table-head px-4 py-3">Goal</th>
                        <th class="table-head px-4 py-3">Source</th>
                        <th class="table-head px-4 py-3">Amount</th>
                        <th class="table-head px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contributions as $contribution)
                        <tr class="table-row">
                            <td class="whitespace-nowrap px-6 py-[15px] text-[12px] text-fg">{{ $contribution->contributed_on->format('M d, Y') }}</td>
                            <td class="whitespace-nowrap px-4 py-[15px] text-[12px] text-fg">{{ $contribution->savingsGoal?->name ?? 'Deleted goal' }}</td>
                            <td class="whitespace-nowrap px-4 py-[15px] text-[12px] text-muted">{{ $contribution->source ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-[15px] text-[12px] font-medium text-success">+₱{{ number_format((float) $contribution->amount, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-[15px] text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button
                                        type="button"
                                        data-modal-open="add-contribution-modal"
                                        data-contribution-edit
                                        data-action="{{ route('contributions.update', $contribution) }}"
                                        data-goal-id="{{ $contribution->savings_goal_id }}"
                                        data-amount="{{ number_format((float) $contribution->amount, 2, '.', '') }}"
                                        data-date="{{ $contribution->contributed_on->toDateString() }}"
                                        data-source="{{ $contribution->source }}"
                                        data-notes="{{ $contribution->notes }}"
                                        class="rounded-lg p-2 text-muted transition hover:bg-white/5 hover:text-primary-soft"
                                        aria-label="Edit contribution"
                                    >
                                        <x-icon name="edit" :size="14" />
                                    </button>
                                    <form action="{{ route('contributions.destroy', $contribution) }}" method="POST" onsubmit="return confirm('Delete this contribution?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-muted transition hover:bg-white/5 hover:text-danger" aria-label="Delete contribution">
                                            <x-icon name="trash" :size="14" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-[12px] text-muted">No contributions recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('modals')
    <div id="add-goal-modal" data-modal class="modal-backdrop hidden">
        <div class="modal-panel max-w-[450px] p-6 sm:p-7" role="dialog" aria-modal="true" aria-labelledby="add-goal-title">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="add-goal-title" class="text-[22px] font-bold tracking-[-0.01em] text-fg">Create Savings Goal</h2>
                    <p class="mt-1 text-[12px] text-muted">Give your next milestone a name.</p>
                </div>
                <button type="button" data-modal-close class="rounded-lg p-1.5 text-muted transition hover:bg-white/5 hover:text-fg" aria-label="Close dialog">
                    <x-icon name="close" :size="18" />
                </button>
            </div>

            <form
                id="goal-form"
                action="{{ route('savings-goals.store') }}"
                method="POST"
                data-store-url="{{ route('savings-goals.store') }}"
                class="mt-6 space-y-4"
            >
                @csrf
                <input id="goal-method" type="hidden" name="_method" value="POST">

                <div>
                    <label for="goal-name" class="label">Goal Name</label>
                    <input id="goal-name" name="name" type="text" class="field" placeholder="e.g. New Laptop" value="{{ old('name') }}" required>
                </div>

                <div>
                    <label for="goal-amount" class="label">Target Amount</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-muted">₱</span>
                        <input id="goal-amount" name="target_amount" type="number" min="0.01" step="0.01" class="field pl-7" placeholder="0.00" value="{{ old('target_amount') }}" required>
                    </div>
                </div>

                <div>
                    <label for="goal-date" class="label">Target Date</label>
                    <input id="goal-date" name="target_date" type="date" class="field" value="{{ old('target_date') }}">
                </div>

                <div>
                    <label for="goal-description" class="label">Description (optional)</label>
                    <textarea id="goal-description" name="description" rows="2" class="field h-auto resize-none py-2.5" placeholder="Add a short note">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" data-modal-close class="btn btn-secondary min-w-[110px]">Cancel</button>
                    <button type="submit" id="goal-submit" class="btn btn-primary min-w-[130px]">Save Goal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="add-contribution-modal" data-modal class="modal-backdrop hidden">
        <div class="modal-panel max-w-[470px] p-6 sm:p-7" role="dialog" aria-modal="true" aria-labelledby="add-contribution-title">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="add-contribution-title" class="text-[22px] font-bold tracking-[-0.01em] text-fg">Add Contribution</h2>
                    <p class="mt-1 text-[12px] text-muted">Record money added to a savings goal.</p>
                </div>
                <button type="button" data-modal-close class="rounded-lg p-1.5 text-muted transition hover:bg-white/5 hover:text-fg" aria-label="Close dialog">
                    <x-icon name="close" :size="18" />
                </button>
            </div>

            <form
                id="contribution-form"
                action="{{ route('contributions.store') }}"
                method="POST"
                data-store-url="{{ route('contributions.store') }}"
                class="mt-6 space-y-4"
            >
                @csrf
                <input id="contribution-method" type="hidden" name="_method" value="POST">

                <div>
                    <label for="contribution-goal" class="label">Savings Goal</label>
                    <select id="contribution-goal" name="savings_goal_id" class="field" required>
                        <option value="">Select goal</option>
                        @foreach ($goals as $goal)
                            <option value="{{ $goal->id }}" @selected((int) old('savings_goal_id') === $goal->id)>{{ $goal->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="contribution-amount" class="label">Contribution Amount</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-muted">₱</span>
                            <input id="contribution-amount" name="amount" type="number" min="0.01" step="0.01" class="field pl-7" placeholder="0.00" value="{{ old('amount') }}" required>
                        </div>
                    </div>
                    <div>
                        <label for="contribution-date" class="label">Date</label>
                        <input id="contribution-date" name="contributed_on" type="date" class="field" value="{{ old('contributed_on', now()->toDateString()) }}" required>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="contribution-source" class="label">Payment Source</label>
                        <select id="contribution-source" name="source" class="field">
                            <option value="">Select source</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source }}" @selected(old('source') === $source)>{{ $source }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="contribution-notes" class="label">Notes (optional)</label>
                        <input id="contribution-notes" name="notes" type="text" class="field" placeholder="Add a short note" value="{{ old('notes') }}">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" data-modal-close class="btn btn-secondary min-w-[110px]">Cancel</button>
                    <button type="submit" id="contribution-submit" class="btn btn-primary min-w-[150px]">Add Contribution</button>
                </div>
            </form>
        </div>
    </div>
@endpush
