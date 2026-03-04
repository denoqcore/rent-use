<?php

use Livewire\Component;

new class extends Component {};
?>

<div x-data="{ open: false, scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })">

    <header class="hidden md:flex fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b"
        :class="scrolled ? 'border-(--background-3) shadow-sm' : 'border-transparent bg-transparent'"
        style="background-color: var(--background);">
        <div class="max-w-6xl w-full mx-auto px-8 flex items-center justify-between gap-8" style="height:72px">

            <div class="flex items-center gap-6">
                <a href="/" class="text-xl font-black tracking-wide text-(--text-primary)">
                    rent<span class="text-(--text-muted) font-normal">.use</span>
                </a>

                <div class="w-px h-4 bg-(--background-2)"></div>

                <nav class="flex items-center gap-0.5">
                    <a href="/search"
                        class="px-3 py-1.5 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-md transition-all">
                        {{ __('messages.browse') }}
                    </a>
                    @auth
                        <a href="/post"
                            class="px-3 py-1.5 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-md transition-all">
                            {{ __('messages.post') }}
                        </a>
                    @endauth
                </nav>
            </div>

            <div class="flex items-center gap-6">

                <nav class="flex items-center gap-0.5">
                    <a href="/"
                        class="px-3 py-1.5 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-md transition-all">
                        Support
                    </a>
                    <a href="/"
                        class="px-3 py-1.5 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-md transition-all">
                        FAQ
                    </a>
                </nav>

                @guest
                    <a href="/login"
                        class="px-5 py-2 rounded-sm bg-(--button) text-(--button-text) text-sm font-medium hover:bg-(--button-h) transition-all active:scale-95">
                        {{ __('messages.started') }}
                    </a>
                @endguest
                @auth
                    <a href="/profile"
                        class="px-3 py-1.5 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-md transition-all">
                        {{ Auth::user()->name }}
                    </a>
                @endauth

                <div class="flex items-center rounded-sm p-0.5 text-xs font-medium"
                    style="background-color: var(--background-2)">
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="px-3 py-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)/30 transition-all">EN</a>
                    <a href="{{ route('lang.switch', 'ro') }}"
                        class="px-3 py-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)/30 transition-all">RO</a>
                </div>

            </div>
        </div>
    </header>

    <div class="hidden md:block" style="height:72px"></div>

    <header class="md:hidden fixed top-0 left-0 right-0 z-50 border-b border-(--background-2)"
        style="background-color: var(--background);">
        <div class="flex items-center justify-between px-4" style="height:52px">

            <a href="/" class="text-sm font-black tracking-wide text-(--text-primary)">
                rent<span class="text-(--text-muted) font-normal">.use</span>
            </a>

            <button @click="open = !open"
                class="text-xs font-medium text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                <span x-show="!open">Menu</span>
                <span x-show="open" x-cloak>{{ __('messages.close') }}</span>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2" @click.away="open = false"
            class="border-t border-(--background-2) px-4 pt-3 pb-5 space-y-1"
            style="background-color: var(--background);">

            <a href="/search" @click="open = false"
                class="block px-2 py-2.5 rounded-md text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                {{ __('messages.browse') }}
            </a>
            <a href="/" @click="open = false"
                class="block px-2 py-2.5 rounded-md text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                Support
            </a>
            <a href="/" @click="open = false"
                class="block px-2 py-2.5 rounded-md text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                FAQ
            </a>

            @auth
                <a href="/post" @click="open = false"
                    class="block px-2 py-2.5 rounded-md text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                    {{ __('messages.post') }}
                </a>
                <a href="/profile" @click="open = false"
                    class="block px-2 py-2.5 rounded-md text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                    {{ Auth::user()->name }}
                </a>
            @endauth

            @guest
                <div class="pt-1">
                    <a href="/login"
                        class="inline-block px-4 py-1.5 rounded-sm bg-(--button) text-(--button-text) text-xs font-medium hover:bg-(--button-h) transition-all active:scale-95">
                        {{ __('messages.started') }}
                    </a>
                </div>
            @endguest

            <div class="flex items-center gap-1 pt-3 border-t border-(--background-2) mt-2">
                <a href="{{ route('lang.switch', 'en') }}"
                    class="px-3 py-1.5 rounded-sm text-xs font-medium text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">EN</a>
                <span class="text-(--text-muted) text-xs opacity-30">/</span>
                <a href="{{ route('lang.switch', 'ro') }}"
                    class="px-3 py-1.5 rounded-sm text-xs font-medium text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">RO</a>
            </div>

        </div>
    </header>

    <div class="md:hidden" style="height:52px"></div>

</div>
