@extends('layouts.guest')

@section('title', 'Log In')

@section('body')
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-5 py-10 sm:px-8">
        <div class="pointer-events-none absolute -left-40 top-1/4 size-[520px] rounded-full bg-primary/10 blur-[130px]"></div>
        <div class="pointer-events-none absolute -right-32 bottom-0 size-[460px] rounded-full bg-primary-deep/10 blur-[120px]"></div>

        <div class="relative grid w-full max-w-[1180px] overflow-hidden rounded-[22px] border border-line bg-ink-900/70 p-6 backdrop-blur-sm sm:p-8 lg:grid-cols-[1.05fr_1fr] lg:p-10 xl:gap-10">
            <div class="flex flex-col justify-center px-2 py-6 lg:px-6">
                <div class="flex items-center gap-3">
                    <span class="text-[44px] font-bold leading-none text-primary">◈</span>
                    <span class="text-[32px] font-bold tracking-[-0.03em] text-fg sm:text-[38px]">PennyWise</span>
                </div>
                <p class="mt-2 pl-1 text-[15px] text-muted">Spend with purpose. Save for more.</p>

                <div class="mt-14 max-w-[420px] lg:mt-20">
                    <h2 class="text-[26px] font-bold leading-snug tracking-[-0.02em] text-fg sm:text-[28px]">Take control of your money.</h2>
                    <p class="mt-3 text-[14px] leading-relaxed text-muted">Track expenses. Plan budgets. Reach your goals.</p>
                </div>

                <div class="mt-10 grid max-w-[420px] gap-3 sm:grid-cols-3">
                    @foreach ([['label' => 'Track', 'value' => 'Every peso'], ['label' => 'Plan', 'value' => 'Smarter budgets'], ['label' => 'Grow', 'value' => 'Real savings']] as $item)
                        <div class="rounded-xl border border-line bg-ink-850/60 px-3.5 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-primary-soft">{{ $item['label'] }}</p>
                            <p class="mt-1 text-[12px] text-fg">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 rounded-2xl border border-line bg-ink-850/85 p-6 shadow-2xl sm:p-8 lg:mt-0 lg:p-10">
                <h1 class="text-[28px] font-bold tracking-[-0.02em] text-fg sm:text-[30px]">Welcome Back</h1>
                <p class="mt-1.5 text-[13px] text-muted">Log in to your account</p>

                @if (session('status'))
                    <div class="mt-5 rounded-xl border border-success/25 bg-success/10 px-4 py-3 text-[12px] text-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-5 rounded-xl border border-danger/25 bg-danger/10 px-4 py-3 text-[12px] text-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.attempt') }}" method="POST" class="mt-7 space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="label">Email or Username</label>
                        <input id="email" name="email" type="text" class="field" placeholder="dummy1 or you@example.com" value="{{ old('email') }}" autocomplete="username" required autofocus>
                    </div>

                    <div>
                        <label for="password" class="label">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" class="field pr-10" placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" data-password-toggle="password" class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] text-muted hover:text-fg">Show</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-1">
                        <label class="flex cursor-pointer items-center gap-2.5 text-[12px] text-muted">
                            <input type="checkbox" name="remember" class="size-4 rounded border-line bg-ink-950 accent-primary" @checked(old('remember'))>
                            Remember me
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[12px] text-primary-soft hover:text-fg">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2 h-[46px] w-full">Log In</button>
                </form>

                <div class="mt-5 rounded-xl border border-primary/20 bg-primary/[0.07] px-4 py-3.5">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.1em] text-muted">Demo accounts</p>
                    <div class="mt-3 space-y-3">
                        @foreach ([
                            ['username' => 'dummy1', 'email' => 'dummy1@pennywise.test', 'password' => 'password1'],
                            ['username' => 'dummy2', 'email' => 'dummy2@pennywise.test', 'password' => 'password2'],
                        ] as $demo)
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-[12px] text-fg">{{ $demo['username'] }}</p>
                                    <p class="mt-0.5 text-[11px] text-muted">{{ $demo['password'] }}</p>
                                </div>
                                <button
                                    type="button"
                                    data-demo-login
                                    data-demo-email="{{ $demo['email'] }}"
                                    data-demo-password="{{ $demo['password'] }}"
                                    class="btn btn-secondary h-9 shrink-0 px-3 text-[11px]"
                                >
                                    Use
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <p class="mt-5 text-center text-[13px] text-muted">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-primary-soft hover:text-fg">Register</a>
                </p>

                <p class="mt-8 text-center text-[11px] leading-relaxed text-muted/80">
                    By continuing, you agree to our Terms and Privacy Policy.
                </p>
            </div>
        </div>
    </div>
@endsection
