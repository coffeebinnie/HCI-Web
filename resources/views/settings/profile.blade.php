@extends('layouts.app')

@section('title', 'My Profile')
@section('subtitle', 'Manage your personal account and review recent account activity.')

@section('content')
    <section class="panel p-5 sm:p-6">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <span class="flex size-[86px] shrink-0 items-center justify-center rounded-full border border-primary/25 bg-primary/10 text-primary-soft">
                    <x-icon name="user" :size="36" />
                </span>
                <div class="min-w-0">
                    <h2 class="truncate text-[20px] font-semibold text-fg">{{ $user['name'] }}</h2>
                    <p class="mt-1 truncate text-[12px] text-muted">{{ $user['email'] }}</p>
                    <span class="chip chip-income mt-2.5">Active account</span>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary h-10 px-4 text-[12px] sm:min-w-[150px]">Edit Profile</a>
        </div>
    </section>

    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['label' => 'Account type', 'value' => $user['account_type']],
            ['label' => 'Currency', 'value' => 'PHP (₱)'],
            ['label' => 'Preferences', 'value' => 'Dark theme'],
        ] as $card)
            <div class="panel p-5">
                <p class="text-[10px] font-semibold uppercase tracking-[0.09em] text-muted">{{ $card['label'] }}</p>
                <p class="mt-2 text-[16px] font-semibold text-fg">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <section class="panel mt-4 p-5 sm:p-6">
        <h2 class="text-[18px] font-semibold text-fg">Recent account activity</h2>
        <p class="mt-1 text-[12px] text-muted">Review your latest changes and account actions.</p>

        <dl class="mt-4 divide-y divide-line-soft">
            @foreach ($activity as $item)
                <div class="grid grid-cols-[90px_1fr_auto] items-center gap-4 py-3.5 sm:grid-cols-[160px_1fr_auto]">
                    <dt class="text-[12px] font-medium text-fg">{{ $item['area'] }}</dt>
                    <dd class="truncate text-[12px] text-muted">{{ $item['detail'] }}</dd>
                    <dd class="text-right text-[11px] text-muted">{{ $item['when'] }}</dd>
                </div>
            @endforeach
        </dl>

        <div class="mt-5 flex justify-end">
            <a href="{{ route('settings') }}" class="btn btn-secondary h-10 px-4 text-[12px] sm:min-w-[170px]">Account Settings</a>
        </div>
    </section>
@endsection
