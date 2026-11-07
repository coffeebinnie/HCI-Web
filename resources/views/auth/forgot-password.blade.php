@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('body')
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-5 py-10">
        <div class="pointer-events-none absolute left-1/2 top-1/3 h-[420px] w-[620px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/10 blur-[120px]"></div>

        <div class="relative w-full max-w-[560px] rounded-[22px] border border-line bg-ink-850/85 p-8 shadow-2xl sm:p-12">
            <div class="flex items-center gap-2.5">
                <span class="text-[30px] font-bold leading-none text-primary">◈</span>
                <span class="text-[22px] font-bold tracking-[-0.02em] text-fg">PennyWise</span>
            </div>

            <h1 class="mt-10 text-[27px] font-bold leading-tight tracking-[-0.02em] text-fg">Forgot your password?</h1>
            <p class="mt-3 max-w-[420px] text-[13px] leading-relaxed text-muted">
                Enter the email address linked to your account. We'll guide you through resetting your password.
            </p>

            <form action="{{ route('password.sent') }}" method="GET" class="mt-8 space-y-5">
                <div>
                    <label for="email" class="label">Email Address</label>
                    <input id="email" name="email" type="email" class="field" placeholder="you@example.com" autocomplete="email" required>
                </div>

                <button type="submit" class="btn btn-primary h-[50px] w-full">Send Reset Link</button>
            </form>

            <a href="{{ route('login') }}" class="mt-6 flex items-center justify-center gap-2 text-[13px] text-muted hover:text-fg">
                <x-icon name="arrow-left" :size="15" />
                Back to Log In
            </a>
        </div>
    </div>
@endsection
