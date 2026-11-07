@props(['label', 'value', 'meta' => null, 'tone' => 'muted'])

@php
    $toneClass = match ($tone) {
        'success' => 'text-success',
        'danger' => 'text-danger',
        'primary' => 'text-primary-soft',
        'warning' => 'text-warning',
        default => 'text-muted',
    };
@endphp

<div class="panel p-5">
    <p class="text-[11px] font-semibold tracking-[0.08em] text-muted">{{ $label }}</p>
    <p class="mt-2 text-[24px] font-bold leading-none tracking-[-0.01em] text-fg">{{ $value }}</p>
    @if ($meta)
        <p class="mt-3 text-[11px] {{ $toneClass }}">{{ $meta }}</p>
    @endif
</div>
