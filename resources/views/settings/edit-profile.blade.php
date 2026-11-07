@extends('layouts.app')

@section('title', 'Edit Profile')
@section('subtitle', 'Update the personal information associated with your PennyWise account.')

@section('content')
    <section class="panel p-5 sm:p-7">
        <div>
            <h2 class="text-[18px] font-semibold text-fg">Personal information</h2>
            <p class="mt-1.5 text-[12px] text-muted">Your changes are shown in this prototype; persistent saving requires a backend.</p>
        </div>

        <form action="{{ route('changes.saved') }}" method="GET" class="mt-6">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="full-name" class="label">Full name</label>
                    <input id="full-name" name="name" type="text" class="field" value="{{ $user['name'] }}" required>
                </div>
                <div>
                    <label for="email" class="label">Email address</label>
                    <input id="email" name="email" type="email" class="field" value="{{ $user['email'] }}" required>
                </div>
                <div>
                    <label for="username" class="label">Username</label>
                    <input id="username" name="username" type="text" class="field" value="{{ $user['username'] }}">
                </div>
                <div>
                    <label for="phone" class="label">Phone number</label>
                    <input id="phone" name="phone" type="tel" class="field" value="{{ $user['phone'] }}">
                </div>
                <div>
                    <label for="currency" class="label">Preferred currency</label>
                    <select id="currency" name="currency" class="field">
                        <option @selected($user['currency'] === 'Philippine Peso (₱ PHP)')>Philippine Peso (₱ PHP)</option>
                        <option>US Dollar ($ USD)</option>
                        <option>Euro (€ EUR)</option>
                    </select>
                </div>
                <div>
                    <label for="timezone" class="label">Timezone</label>
                    <select id="timezone" name="timezone" class="field">
                        <option @selected($user['timezone'] === 'Asia/Manila (UTC+8)')>Asia/Manila (UTC+8)</option>
                        <option>Asia/Tokyo (UTC+9)</option>
                        <option>UTC (UTC+0)</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-primary/20 bg-primary/[0.07] px-4 py-3.5">
                <p class="text-[12px] leading-relaxed text-primary-soft">Tip: Keep your account details up to date so your profile stays accurate.</p>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-line-soft pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('profile') }}" class="btn btn-secondary sm:min-w-[130px]">Cancel</a>
                <button type="submit" class="btn btn-primary sm:min-w-[170px]">Save Changes</button>
            </div>
        </form>
    </section>
@endsection
