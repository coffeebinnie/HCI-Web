@props(['categories' => [], 'value' => '₱17,200', 'label' => 'Total spent', 'size' => 132])

@php
    $radius = 48;
    $circumference = 2 * M_PI * $radius;
    $segments = [];
    $offset = 0;

    foreach ($categories as $index => $category) {
        $length = $circumference * ($category['percent'] / 100);
        $segments[] = [
            'color' => $category['color'],
            'length' => $length,
            'gap' => $circumference - $length,
            'offset' => -$offset,
            'delay' => $index * 90,
        ];
        $offset += $length;
    }
@endphp

<div class="flex flex-col items-center gap-6 sm:flex-row sm:justify-between">
    <div class="chart-donut relative shrink-0" style="width: {{ $size }}px; height: {{ $size }}px; --circumference: {{ $circumference }}">
        <svg viewBox="0 0 120 120" class="size-full -rotate-90" role="img" aria-label="{{ $label }}">
            <circle cx="60" cy="60" r="{{ $radius }}" fill="none" stroke="#26272f" stroke-width="20" />
            @foreach ($segments as $segment)
                <circle
                    cx="60"
                    cy="60"
                    r="{{ $radius }}"
                    fill="none"
                    stroke="{{ $segment['color'] }}"
                    stroke-width="20"
                    stroke-dasharray="{{ $segment['length'] }} {{ $segment['gap'] }}"
                    stroke-dashoffset="{{ $segment['offset'] }}"
                    class="chart-donut-segment"
                    style="--segment-length: {{ $segment['length'] }}; --segment-gap: {{ $segment['gap'] }}; --segment-delay: {{ $segment['delay'] }}ms"
                />
            @endforeach
        </svg>
        <div class="absolute inset-[18%] flex flex-col items-center justify-center rounded-full bg-ink-850">
            <p class="text-[15px] font-bold text-fg">{{ $value }}</p>
            <p class="mt-0.5 text-[10px] text-muted">{{ $label }}</p>
        </div>
    </div>

    <div class="w-full max-w-[210px] space-y-3">
        @foreach ($categories as $category)
            <div class="flex items-center justify-between gap-3 text-[12px]">
                <span class="flex items-center gap-2.5 text-muted">
                    <span class="size-2.5 rounded-full" style="background-color: {{ $category['color'] }}"></span>
                    {{ $category['label'] }}
                </span>
                <span class="font-semibold text-fg">{{ $category['percent'] }}%</span>
            </div>
        @endforeach
    </div>
</div>
