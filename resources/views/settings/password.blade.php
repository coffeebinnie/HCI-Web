@extends('layouts.app')

@section('title', 'Change Password')
@section('subtitle', 'Keep your account secure by updating your password.')

@section('content')
    <section class="panel p-5 sm:p-7">
        <div>
            <h2 class="text-[18px] font-semibold text-fg">Password security</h2>
            <p class="mt-1.5 text-[12px] text-muted">Use a strong password you do not use elsewhere.</p>
        </div>

        <form action="{{ route('settings.password.update') }}" method="POST" class="mt-6 grid gap-7 xl:grid-cols-[1fr_300px]">
            @csrf
            <div class="space-y-5">
                <div>
                    <label for="current-password" class="label">Current password</label>
                    <input id="current-password" name="current_password" type="password" class="field" placeholder="Enter current password" autocomplete="current-password" required>
                </div>

                <div>
                    <label for="new-password" class="label">New password</label>
                    <input id="new-password" name="password" type="password" class="field" placeholder="Enter new password" autocomplete="new-password" required>
                </div>

                <div>
                    <label for="confirm-password" class="label">Confirm new password</label>
                    <input id="confirm-password" name="password_confirmation" type="password" class="field" placeholder="Re-enter new password" autocomplete="new-password" required>
                </div>

                <button type="button" data-password-toggle="new-password" class="text-[11px] text-muted hover:text-fg">
                    Show password ◉
                </button>
            </div>

            <aside class="panel-flat h-fit p-5">
                <p class="text-[12px] font-semibold text-fg">Password checklist</p>
                <ul class="mt-4 space-y-3 text-[12px] text-muted">
                    <li class="flex items-start gap-2.5"><span class="mt-0.5 text-success">✓</span> At least 8 characters</li>
                    <li class="flex items-start gap-2.5"><span class="mt-0.5 text-success">✓</span> Mix letters and numbers</li>
                    <li class="flex items-start gap-2.5"><span class="mt-0.5 text-success">✓</span> New password matches</li>
                </ul>
            </aside>

            <div class="flex flex-col-reverse gap-3 border-t border-line-soft pt-5 sm:flex-row sm:justify-end xl:col-span-2">
                <a href="{{ route('settings') }}" class="btn btn-secondary sm:min-w-[130px]">Cancel</a>
                <button type="submit" class="btn btn-primary sm:min-w-[180px]">Update Password</button>
            </div>
        </form>
    </section>
@endsection
