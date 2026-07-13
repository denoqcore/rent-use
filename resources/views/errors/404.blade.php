@extends('layouts.layout')

@section('title', 'rent.use | 404')

@section('content')

    <div class="min-h-[70vh] flex items-center justify-center px-4">
        <div class="flex flex-col items-center text-center max-w-md">

            <span class="text-7xl sm:text-8xl font-black tracking-tight text-(--text-primary) leading-none">
                404
            </span>

            <div class="mt-2 mb-6 flex items-center gap-2">
                <span class="h-px w-8 bg-(--background-3)"></span>
                <x-heroicon-o-face-frown class="w-4 h-4 text-(--text-muted)" />
                <span class="h-px w-8 bg-(--background-3)"></span>
            </div>

            <h1 class="text-lg font-bold text-(--text-primary) mb-2">
                {{ __('messages.404-title') }}
            </h1>

            <p class="text-sm text-(--text-muted) leading-relaxed mb-8">
                {{ __('messages.404-description') }}
            </p>

            <div class="flex items-center gap-3">
                <a href="/"
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-sm text-sm font-semibold bg-(--button) text-(--button-text) hover:bg-(--button-h) transition-colors">
                    <x-heroicon-o-home class="w-4 h-4" />
                    {{ __('messages.404-home') }}
                </a>
                <a href="{{ route('search') }}"
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-sm text-sm font-medium border border-(--background-3) text-(--text-primary) hover:bg-(--background-2) transition-colors">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                    {{ __('messages.404-browse') }}
                </a>
            </div>

        </div>
    </div>

@endsection
