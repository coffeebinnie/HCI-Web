@extends('layouts.app')

@section('title', 'Add contribution')
@section('subtitle', 'Record money added to one of your savings goals.')

@section('content')
    <section class="panel p-5 sm:p-7">
        @if ($goal)
            @php
                $saved = $goal->savedAmount();
                $percent = $goal->progressPercent();
            @endphp

            <div class="panel-flat flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.1em] text-muted">Current savings goal</p>
                    <h2 class="mt-2 text-[20px] font-semibold text-fg">{{ $goal->name }}</h2>
                </div>
                <div class="sm:text-right">
                    <p class="text-[22px] font-bold leading-none text-fg">₱{{ number_format($saved, 2) }}</p>
                    <p class="mt-1.5 text-[11px] text-muted">of ₱{{ number_format((float) $goal->target_amount, 2) }} saved</p>
                </div>
            </div>

            <div class="mt-4">
                <div class="progress-track">
                    <div class="progress-bar" style="width: {{ $percent }}%"></div>
                </div>
                <div class="mt-2 flex items-center justify-between text-[11px] text-muted">
                    <span>{{ $percent }}% complete</span>
                    <span>Target date: {{ $goal->target_date?->format('M d, Y') ?? 'Not set' }}</span>
                </div>
            </div>

            <form action="{{ route('contributions.store') }}" method="POST" class="mt-8">
                @csrf

                <h2 class="text-[18px] font-semibold text-fg">Contribution details</h2>

                <div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="contribution-goal" class="label">Savings goal</label>
                        <select id="contribution-goal" name="savings_goal_id" class="field" required>
                            @foreach ($goals as $option)
                                <option value="{{ $option->id }}" @selected((int) old('savings_goal_id', $goal->id) === $option->id)>{{ $option->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="contribution-amount" class="label">Contribution amount (₱)</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-[13px] text-muted">₱</span>
                            <input
                                id="contribution-amount"
                                name="amount"
                                type="number"
                                min="0.01"
                                step="0.01"
                                class="field pl-7"
                                value="{{ old('amount') }}"
                                required
                            >
                        </div>
                    </div>

                    <div>
                        <label for="contribution-date" class="label">Date</label>
                        <input id="contribution-date" name="contributed_on" type="date" class="field" value="{{ old('contributed_on', now()->toDateString()) }}" required>
                    </div>

                    <div>
                        <label for="contribution-source" class="label">Payment source</label>
                        <select id="contribution-source" name="source" class="field">
                            <option value="">Select source</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source }}" @selected(old('source') === $source)>{{ $source }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="contribution-notes" class="label">Notes (optional)</label>
                        <input id="contribution-notes" name="notes" type="text" class="field" value="{{ old('notes') }}" placeholder="Add a short note">
                    </div>
                </div>

                <div class="mt-7 flex flex-col-reverse gap-3 border-t border-line-soft pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('savings') }}" class="btn btn-secondary sm:min-w-[130px]">Cancel</a>
                    <button type="submit" class="btn btn-primary sm:min-w-[170px]">Add contribution</button>
                </div>
            </form>
        @else
            <div class="py-10 text-center">
                <h2 class="text-[18px] font-semibold text-fg">No savings goals yet</h2>
                <p class="mt-2 text-[12px] text-muted">Create a goal first, then you can record contributions.</p>
                <a href="{{ route('savings') }}" class="btn btn-primary mt-6">Go to Savings</a>
            </div>
        @endif
    </section>
@endsection
