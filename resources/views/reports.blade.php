@extends('layouts.app')

@section('title', 'Financial Reports')
@section('subtitle', 'Understand where your money goes.')

@section('content')
    <div class="flex justify-end">
        <button type="button" class="field flex w-auto items-center gap-2.5 px-4 text-[12px]">
            <x-icon name="calendar" :size="15" class="text-muted" />
            <span>{{ $period }}</span>
            <span class="text-muted">▾</span>
        </button>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        @foreach ($stats as $stat)
            <x-stat-card :label="$stat['label']" :value="$stat['value']" :tone="$stat['tone']" />
        @endforeach
    </div>

    <div class="mt-5 grid gap-5 xl:grid-cols-5">
        <section class="panel p-5 sm:p-6 xl:col-span-3">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-[18px] font-semibold text-fg">Income vs. Expenses</h2>
                    <p class="mt-1 text-[12px] text-muted">Monthly overview</p>
                </div>
            </div>
            <div class="mt-5">
                <x-bar-chart :months="$months" :height="230" />
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

    <section class="panel mt-5 p-5 sm:p-6">
        <h2 class="text-[18px] font-semibold text-fg">Monthly Summary</h2>

        <dl class="mt-4 divide-y divide-line-soft">
            @foreach ($summary as $row)
                <div class="flex items-center justify-between gap-4 py-3.5">
                    <dt class="text-[13px] text-muted">{{ $row['label'] }}</dt>
                    <dd class="text-[13px] font-semibold text-fg">{{ $row['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    </section>
@endsection
