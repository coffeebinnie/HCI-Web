@extends('layouts.guest')

@section('title', 'Create Account')

@section('body')
    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-5 py-10">
        <div class="pointer-events-none absolute left-1/2 top-0 h-[480px] w-[720px] -translate-x-1/2 rounded-full bg-primary/10 blur-[130px]"></div>

        <div class="relative w-full max-w-[580px]">
            <div class="mb-7 flex flex-col items-center">
                <span class="text-[42px] font-bold leading-none text-primary">◈</span>
                <span class="mt-1 text-[30px] font-bold tracking-[-0.03em] text-fg sm:text-[34px]">PennyWise</span>
                <span class="mt-1 text-[11px] text-muted">Spend with purpose. Save for more.</span>
            </div>

            <div class="rounded-[20px] border border-line bg-ink-850/85 p-7 shadow-2xl sm:p-10">
                <h1 class="text-[26px] font-bold tracking-[-0.02em] text-fg sm:text-[28px]">Create Your Account</h1>
                <p class="mt-1.5 text-[13px] text-muted">Start managing your finances today.</p>

                @if ($errors->any())
                    <div class="mt-5 rounded-xl border border-danger/25 bg-danger/10 px-4 py-3 text-[12px] text-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('register.attempt') }}" method="POST" class="mt-7 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="label">Full Name</label>
                        <input id="name" name="name" type="text" class="field" placeholder="Your full name" value="{{ old('name') }}" autocomplete="name" required autofocus>
                    </div>

                    <div>
                        <label for="email" class="label">Email Address</label>
                        <input id="email" name="email" type="email" class="field" placeholder="you@example.com" value="{{ old('email') }}" autocomplete="email" required>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="password" class="label">Password</label>
                            <input id="password" name="password" type="password" class="field" placeholder="At least 8 characters" autocomplete="new-password" required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="label">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="field" placeholder="Confirm your password" autocomplete="new-password" required>
                        </div>
                    </div>

                    <label class="flex cursor-pointer items-start gap-2.5 pt-1 text-[12px] leading-relaxed text-muted">
                        <input type="checkbox" name="terms" class="mt-0.5 size-4 rounded border-line bg-ink-950 accent-primary" @checked(old('terms')) required>
                        <span>I agree to the <span class="text-primary-soft">Terms</span> and <span class="text-primary-soft">Privacy Policy</span>.</span>
                    </label>

                    <button type="submit" class="btn btn-primary mt-2 h-[48px] w-full">Register</button>
                </form>

                <p class="mt-6 text-center text-[13px] text-muted">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-primary-soft hover:text-fg">Log In</a>
                </p>
            </div>
        </div>
    </div>
@endsection
