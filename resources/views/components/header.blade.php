<?php

use Livewire\Component;

new class extends Component {};
?>

<div x-data="{ open: false }">

    {{-- Desktop --}}
    <header class="hidden md:block border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">

            <a href="/" class="text-sm font-semibold tracking-tight">rent.use</a>

            <nav class="flex items-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                <a href="/search"
                    class="hover:text-black dark:hover:text-white transition-colors">{{ __('messages.browse') }}</a>
                @auth
                    <a href="/create"
                        class="hover:text-black dark:hover:text-white transition-colors">{{ __('messages.post') }}</a>
                @endauth
            </nav>

            <div class="flex items-center gap-3">
                @guest
                    <flux:button size="sm" href="/login">
                        {{ __('messages.started') }}
                    </flux:button>
                @endguest
                @auth
                    <a href="/profile"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
                        {{ Auth::user()->name }}
                    </a>
                @endauth

                <div class="flex items-center gap-1 text-xs text-gray-400">
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="hover:text-black dark:hover:text-white transition-colors px-1">EN</a>
                    <span class="text-gray-200 dark:text-gray-700">/</span>
                    <a href="{{ route('lang.switch', 'ro') }}"
                        class="hover:text-black dark:hover:text-white transition-colors px-1">RO</a>
                </div>

                <flux:button size="sm" @click="$flux.dark = !$flux.dark" icon="moon" variant="subtle"
                    aria-label="Toggle dark mode" />
            </div>

        </div>
    </header>

    {{-- Mobile --}}
    <header
        class="md:hidden fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between h-12 px-4">

            <a href="/" class="text-sm font-semibold tracking-tight">rent.use</a>

            <button @click="open = !open" class="text-xs font-medium text-gray-500 w-10 text-right cursor-pointer">
                <span x-show="!open">Menu</span>
                <span x-show="open" x-cloak>Close</span>
            </button>

        </div>

        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1" @click.away="open = false"
            class="border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-950 px-4 py-4 space-y-3">

            <a href="/search" @click="open = false"
                class="block text-sm text-gray-600 dark:text-gray-400 py-1.5 hover:text-black dark:hover:text-white transition-colors">
                {{ __('messages.browse') }}
            </a>
            @auth
                <a href="/create" @click="open = false"
                    class="block text-sm text-gray-600 dark:text-gray-400 py-1.5 hover:text-black dark:hover:text-white transition-colors">
                    {{ __('messages.post') }}
                </a>
                <a href="/profile" @click="open = false"
                    class="block text-sm text-gray-600 dark:text-gray-400 py-1.5 hover:text-black dark:hover:text-white transition-colors">
                    {{ Auth::user()->name }}
                </a>
            @endauth
            @guest
                <a href="/login" class="block text-sm font-medium py-1.5">
                    Get Started →
                </a>
            @endguest

            <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-3 text-xs text-gray-400">
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="hover:text-black dark:hover:text-white transition-colors">EN</a>
                    <span class="text-gray-200 dark:text-gray-700">/</span>
                    <a href="{{ route('lang.switch', 'ro') }}"
                        class="hover:text-black dark:hover:text-white transition-colors">RO</a>
                </div>
                <flux:button size="sm" @click="$flux.dark = !$flux.dark" icon="moon" variant="subtle"
                    aria-label="Toggle dark mode" />
            </div>

        </div>
    </header>

    {{-- Mobile spacer --}}
    <div class="md:hidden h-12"></div>

</div>
