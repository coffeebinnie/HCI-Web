@props(['months' => [], 'height' => 210, 'incomeLabel' => 'Income', 'expenseLabel' => 'Expenses'])

@php
    $maximum = max(1, collect($months)->max(fn ($month) => max($month['income'], $month['expense'])));
    $lines = [100, 75, 50, 25];
@endphp

<div>
    <div class="flex items-center gap-5 text-[11px] text-muted">
        <span class="flex items-center gap-2"><span class="size-2 rounded-[2px] bg-primary"></span>{{ $incomeLabel }}</span>
        <span class="flex items-center gap-2"><span class="size-2 rounded-[2px] bg-[#3c3d4a]"></span>{{ $expenseLabel }}</span>
    </div>

    <div class="relative mt-5" style="height: {{ $height }}px">
        <div class="pointer-events-none absolute inset-0 flex flex-col justify-between">
            @foreach ($lines as $line)
                <div class="border-t border-line-soft/80"></div>
            @endforeach
            <div class="border-t border-line-soft/80"></div>
        </div>

        <div class="relative flex h-full items-end justify-between gap-2 px-1 sm:gap-4 sm:px-3">
            @foreach ($months as $month)
                <div class="group flex h-full flex-1 items-end justify-center gap-1 sm:gap-1.5">
                    <div class="relative flex h-full w-full max-w-[34px] items-end justify-center gap-1">
                        <div
                            class="chart-bar w-[46%] max-w-[13px] rounded-t-[3px] bg-gradient-to-t from-primary-deep to-primary-soft transition-opacity group-hover:opacity-100"
                            style="height: {{ max(4, ($month['income'] / $maximum) * 100) }}%; --bar-delay: {{ $loop->index * 70 }}ms"
                            title="{{ $incomeLabel }}: {{ $month['income'] }}"
                        ></div>
                        <div
                            class="chart-bar w-[46%] max-w-[13px] rounded-t-[3px] bg-[#3c3d4a] transition-colors group-hover:bg-[#4a4b59]"
                            style="height: {{ max(4, ($month['expense'] / $maximum) * 100) }}%; --bar-delay: {{ $loop->index * 70 + 40 }}ms"
                            title="{{ $expenseLabel }}: {{ $month['expense'] }}"
                        ></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-2 flex justify-between gap-2 px-1 sm:gap-4 sm:px-3">
        @foreach ($months as $month)
            <span class="flex-1 text-center text-[10px] text-muted">{{ $month['label'] }}</span>
        @endforeach
    </div>
</div>
