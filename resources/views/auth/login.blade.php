@extends('layouts.layout')

@section('content')
    <div class="min-h-[calc(92vh-72px)] flex items-center justify-center px-6 py-12"
        style="background-color: var(--background)">

        <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-12 items-center">

            <div class="w-full max-w-md mx-auto lg:mx-0">

                <div class="mb-8">
                    <h1 class="text-3xl lg:text-4xl font-black text-(--text-primary) mb-2">
                        {{ __('messages.login-title') }}
                    </h1>
                    <p class="text-sm text-(--text-muted)">
                        {{ __('messages.login-subtitle') }}
                        <a href="/register" class="text-(--text-primary) hover:underline underline-offset-4 transition-all">
                            {{ __('messages.login-subtitle-link') }}
                        </a>
                    </p>
                </div>

                <div class="flex items-start mb-10 gap-2 rounded-md border border-red-500/30 bg-red-500/10 px-3.5 py-2.5">
                    <span class="mt-0.5 shrink-0">
                        <x-heroicon-o-exclamation-triangle class="h-4 w-4 text-red-700" />
                    </span>
                    <p class="text-sm leading-relaxed text-red-700">
                        {!! __('messages.register-disclaimer') !!}
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-sm border border-red-400/20 bg-red-400/5">
                        @foreach ($errors->all() as $error)
                            <p class="text-sm text-red-400">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                    @csrf

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-(--text-muted)">{{ __('messages.login-email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="px-4 py-3 rounded-sm text-sm text-(--text-primary) placeholder:text-(--text-muted)
                                   border border-transparent focus:border-(--background-3) focus:outline-none transition-all
                                   @error('email') @enderror"
                            style="background-color: var(--background-2)">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-medium text-(--text-muted)">{{ __('messages.login-pass') }}</label>
                            <a href="#"
                                class="text-xs text-(--text-muted) hover:text-(--text-primary) underline-offset-4 hover:underline transition-all">
                                {{ __('messages.login-forgot') }}
                            </a>
                        </div>
                        <input type="password" name="password"
                            class="px-4 py-3 rounded-sm text-sm text-(--text-primary) placeholder:text-(--text-muted)
                                   border border-transparent focus:border-(--background-3) focus:outline-none transition-all
                                   @error('password') @enderror"
                            style="background-color: var(--background-2)">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-4 h-4 rounded-sm accent-(--button) cursor-pointer">
                        <label for="remember" class="text-xs text-(--text-muted) cursor-pointer select-none">
                            {{ __('messages.login-remember') }}
                        </label>
                    </div>

                    <button type="submit"
                        class="mt-2 w-full py-3 rounded-sm text-sm font-semibold text-(--button-text)
                               bg-(--button) hover:bg-(--button-h) transition-all active:scale-95 cursor-pointer">
                        {{ __('messages.login-submit') }}
                    </button>

                </form>
            </div>

            <div class="relative h-80 lg:h-140 rounded-sm overflow-hidden flex flex-col justify-end"
                style="background-color: var(--background-2)">

                <div
                    class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden">
                    <span class="text-[100px] lg:text-[140px] font-black leading-none tracking-tight whitespace-nowrap"
                        style="color: var(--background-1); opacity: 0.05">
                        rent<span class="text-(--text-accent) font-normal">.use</span>
                    </span>
                </div>

                <div class="relative p-6 lg:p-10"
                    style="background: linear-gradient(to top, var(--background), transparent)">
                    <h2 class="text-xl lg:text-2xl font-black text-(--text-primary) mb-2">
                        {{ __('messages.login-board') }}
                    </h2>
                    <p class="text-xs lg:text-sm text-(--text-muted) max-w-62.5 lg:max-w-xs">
                        {{ __('messages.login-desc') }}
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
