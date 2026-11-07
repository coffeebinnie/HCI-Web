@extends('layouts.app')

@section('title', 'Privacy Options')
@section('subtitle', 'Choose how your PennyWise account handles optional privacy preferences.')

@section('content')
    <section class="panel p-5 sm:p-7">
        <div>
            <h2 class="text-[18px] font-semibold text-fg">Data and privacy preferences</h2>
            <p class="mt-1.5 text-[12px] text-muted">These prototype controls demonstrate the intended interaction states.</p>
        </div>

        <form action="{{ route('changes.saved') }}" method="GET" class="mt-6">
            <div class="divide-y divide-line-soft">
                @foreach ($preferences as $preference)
                    <div class="flex items-center justify-between gap-6 py-4">
                        <div>
                            <p class="text-[13px] font-medium text-fg">{{ $preference['label'] }}</p>
                            <p class="mt-1 text-[11px] leading-relaxed text-muted">{{ $preference['description'] }}</p>
                        </div>
                        <button
                            type="button"
                            class="toggle"
                            role="switch"
                            aria-checked="{{ $preference['enabled'] ? 'true' : 'false' }}"
                            aria-label="{{ $preference['label'] }}"
                            data-toggle
                        >
                            <span class="toggle-knob"></span>
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-line-soft pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('settings') }}" class="btn btn-secondary sm:min-w-[130px]">Cancel</a>
                <button type="submit" class="btn btn-primary sm:min-w-[180px]">Save Preferences</button>
            </div>
        </form>
    </section>
@endsection
