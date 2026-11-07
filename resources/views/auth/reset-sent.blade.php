@extends('layouts.guest')

@section('title', 'Check Your Email')

@section('body')
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-5 py-10">
        <div class="pointer-events-none absolute left-1/2 top-1/3 h-[420px] w-[620px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/10 blur-[120px]"></div>

        <div class="relative w-full max-w-[560px] rounded-[22px] border border-line bg-ink-850/85 p-8 shadow-2xl sm:p-12">
            <div class="mx-auto flex size-[86px] items-center justify-center rounded-full border border-primary/25 bg-primary/10 text-primary-soft">
                <x-icon name="mail" :size="36" />
            </div>

            <h1 class="mt-7 text-center text-[26px] font-bold tracking-[-0.02em] text-fg">Check your email</h1>
            <p class="mx-auto mt-3 max-w-[430px] text-center text-[13px] leading-relaxed text-muted">
                If an account exists for that email, you'll receive instructions to reset your password.
            </p>

            <a href="{{ route('login') }}" class="btn btn-primary mt-8 h-[50px] w-full">Return to Log In</a>

            <p class="mt-6 text-center text-[12px] leading-relaxed text-muted/80">
                Didn't receive it? Check your spam folder or try again.
            </p>
        </div>
    </div>
@endsection
