@php
    $navigation = $navigation ?? [];
    $user = $user ?? ['name' => 'Your Name', 'email' => 'you@example.com'];
@endphp

<div id="sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-black/60 backdrop-blur-sm lg:hidden"></div>

<aside
    id="app-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-[238px] -translate-x-full flex-col border-r border-line bg-ink-900 px-4 pb-5 pt-6 transition-transform duration-200 ease-out lg:translate-x-0"
>
    <div class="px-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <span class="text-[26px] font-bold leading-none text-primary">◈</span>
            <span class="text-[21px] font-bold tracking-[-0.02em] text-fg">PennyWise</span>
        </a>
        <p class="mt-1.5 text-[10px] tracking-[0.01em] text-muted">Spend with purpose. Save for more.</p>
    </div>

    <div class="my-5 h-px bg-line"></div>

    <nav class="flex flex-1 flex-col gap-1" aria-label="Main navigation">
        @foreach ($navigation as $item)
            <a
                href="{{ route($item['route']) }}"
                data-nav-match="{{ $item['paths'] }}"
                class="nav-link {{ request()->routeIs($item['route']) ? 'nav-link-active' : '' }}"
            >
                <x-icon :name="$item['icon']" :size="17" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-6">
        <a
            href="{{ route('profile') }}"
            data-nav-match="/profile"
            class="nav-link {{ request()->routeIs('profile', 'profile.edit') ? 'nav-link-active' : '' }}"
        >
            <x-icon name="user" :size="16" />
            <span>Account</span>
        </a>

        <div class="my-3 h-px bg-line"></div>

        <a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings*') ? 'nav-link-active' : '' }}">
            <x-icon name="settings" :size="16" />
            <span>Settings</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full text-left">
                <x-icon name="logout" :size="16" />
                <span>Log Out</span>
            </button>
        </form>
    </div>

    <div class="mt-4 hidden items-center gap-3 rounded-xl border border-line bg-ink-850/60 px-3 py-2.5 lg:flex">
        <span class="flex size-8 items-center justify-center rounded-full bg-primary/15 text-[11px] font-semibold text-primary-soft">
            {{ strtoupper(substr($user['name'] ?? 'Y', 0, 1)) }}
        </span>
        <div class="min-w-0">
            <p class="truncate text-[12px] font-medium text-fg">{{ $user['name'] ?? 'Your Name' }}</p>
            <p class="truncate text-[10px] text-muted">{{ $user['email'] ?? 'you@example.com' }}</p>
        </div>
    </div>
</aside>
