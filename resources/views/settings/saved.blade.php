@extends('layouts.app')

@section('title', 'Changes Saved')
@section('subtitle', 'Your prototype action has completed successfully.')

@section('content')
    <section class="panel mx-auto flex max-w-[900px] flex-col items-center px-6 py-14 text-center sm:px-10">
        <span class="flex size-[92px] items-center justify-center rounded-full border border-success/25 bg-success/10 text-success">
            <x-icon name="check" :size="42" />
        </span>

        <h2 class="mt-7 text-[22px] font-bold tracking-[-0.01em] text-fg">Your changes have been saved</h2>
        <p class="mx-auto mt-3 max-w-[560px] text-[13px] leading-relaxed text-muted">
            This is a prototype confirmation. Actual account updates require backend integration.
        </p>

        <a href="{{ route('profile') }}" class="btn btn-primary mt-8 sm:min-w-[180px]">Back to Profile</a>
    </section>
@endsection
