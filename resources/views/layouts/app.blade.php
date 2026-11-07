<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — PennyWise</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-ink-950">
    @include('partials.sidebar')

    <div class="min-h-screen lg:pl-[238px]">
        <header class="flex flex-col gap-5 px-5 pb-2 pt-6 sm:px-6 lg:flex-row lg:items-start lg:justify-between lg:px-9 lg:pt-7 xl:px-12">
            <div class="flex items-start gap-3">
                <button
                    type="button"
                    data-sidebar-toggle
                    class="mt-0.5 flex size-10 shrink-0 items-center justify-center rounded-xl border border-line bg-ink-850 text-muted hover:text-fg lg:hidden"
                    aria-label="Open navigation"
                >
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></svg>
                </button>
                <div>
                    <h1 class="text-[26px] font-bold leading-tight tracking-[-0.02em] text-fg sm:text-[28px]">@yield('title', 'Dashboard')</h1>
                    <p class="mt-1.5 text-[13px] text-muted">@yield('subtitle', 'Here is your financial overview.')</p>
                </div>
            </div>

            <div class="flex items-center gap-3 lg:pt-0.5">
                @php
                    $searchRoute = match (true) {
                        request()->routeIs('budgets*') => route('budgets'),
                        request()->routeIs('savings*') => route('savings'),
                        default => route('transactions'),
                    };
                    $searchLabel = match (true) {
                        request()->routeIs('budgets*') => 'Search budgets',
                        request()->routeIs('savings*') => 'Search savings goals',
                        default => 'Search transactions',
                    };
                @endphp
                <form action="{{ $searchRoute }}" method="GET" class="relative hidden sm:block" role="search">
                    <button type="submit" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-muted transition hover:text-fg" aria-label="{{ $searchLabel }}">
                        <x-icon name="search" :size="15" />
                    </button>
                    <input
                        name="q"
                        type="search"
                        class="field w-[220px] border-line bg-ink-850/80 pl-10 xl:w-[250px]"
                        placeholder="{{ $searchLabel }}..."
                        aria-label="{{ $searchLabel }}"
                        value="{{ request('q') }}"
                    >
                </form>
            </div>
        </header>

        @if (session('status'))
            <div class="mx-5 mt-5 flex items-start gap-2.5 rounded-xl border border-success/25 bg-success/10 px-4 py-3 text-[12px] text-success sm:mx-6 lg:mx-9 xl:mx-12">
                <x-icon name="check" :size="15" class="mt-px shrink-0" />
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mx-5 mt-5 rounded-xl border border-danger/25 bg-danger/10 px-4 py-3 text-[12px] text-danger sm:mx-6 lg:mx-9 xl:mx-12">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-1.5 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main class="px-5 pb-10 pt-6 sm:px-6 lg:px-9 xl:px-12">
            @yield('content')
        </main>
    </div>

    @include('partials.modals')
    @stack('modals')
</body>
</html>
