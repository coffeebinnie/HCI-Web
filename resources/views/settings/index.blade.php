@extends('layouts.app')

@section('title', 'Settings')
@section('subtitle', 'Manage your account and app preferences.')

@section('content')
    <section class="panel p-5 sm:p-7">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <span class="flex size-[54px] shrink-0 items-center justify-center rounded-full border border-primary/25 bg-primary/10 text-primary-soft">
                    <x-icon name="user" :size="22" />
                </span>
                <div class="min-w-0">
                    <h2 class="text-[18px] font-semibold text-fg">Profile</h2>
                    <p class="mt-1 text-[12px] text-muted">Your personal information</p>
                    <p class="mt-2 truncate text-[13px] font-medium text-fg">{{ $user['name'] }}</p>
                    <p class="truncate text-[11px] text-muted">{{ $user['email'] }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-secondary h-10 px-4 text-[12px] sm:min-w-[150px]">Edit profile</a>
        </div>

        <div class="my-7 h-px bg-line-soft"></div>

        <div>
            <h2 class="text-[18px] font-semibold text-fg">Preferences</h2>
            <p class="mt-1 text-[12px] text-muted">Customize how PennyWise works for you.</p>

            <div class="mt-5 space-y-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[13px] font-medium text-fg">Currency</p>
                        <p class="mt-1 text-[11px] text-muted">Used for totals and reports</p>
                    </div>
                    <select class="field sm:w-[250px]" aria-label="Currency">
                        <option>Philippine Peso (₱ PHP)</option>
                        <option>US Dollar ($ USD)</option>
                        <option>Euro (€ EUR)</option>
                    </select>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[13px] font-medium text-fg">Appearance</p>
                        <p class="mt-1 text-[11px] text-muted">Theme for your workspace</p>
                    </div>
                    <select class="field sm:w-[250px]" aria-label="Appearance">
                        <option>Dark</option>
                        <option>Light</option>
                        <option>System</option>
                    </select>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[13px] font-medium text-fg">Notifications</p>
                        <p class="mt-1 text-[11px] text-muted">Budget reminders and weekly summaries</p>
                    </div>
                    <button type="button" class="toggle" role="switch" aria-checked="true" aria-label="Notifications" data-toggle>
                        <span class="toggle-knob"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="my-7 h-px bg-line-soft"></div>

        <div>
            <h2 class="text-[18px] font-semibold text-fg">Security</h2>

            <div class="mt-5 space-y-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[13px] font-medium text-fg">Password</p>
                        <p class="mt-1 text-[11px] text-muted">Manage your password settings</p>
                    </div>
                    <a href="{{ route('settings.password') }}" class="btn btn-secondary h-10 px-4 text-[12px] sm:min-w-[150px]">Change Password</a>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[13px] font-medium text-fg">Privacy</p>
                        <p class="mt-1 text-[11px] text-muted">Review your personal data preferences</p>
                    </div>
                    <a href="{{ route('settings.privacy') }}" class="btn btn-secondary h-10 px-4 text-[12px] sm:min-w-[150px]">Privacy Options</a>
                </div>
            </div>
        </div>

        <div class="my-7 h-px bg-line-soft"></div>

        <form action="{{ route('changes.saved') }}" method="GET" class="flex flex-col-reverse gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-[11px] text-muted">Prototype screen only. Changes are not saved to a database.</p>
            <button type="submit" class="btn btn-primary sm:min-w-[180px]">Save changes</button>
        </form>
    </section>
@endsection
