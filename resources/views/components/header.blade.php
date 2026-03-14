<?php

use Livewire\Component;

new class extends Component {};
?>

<div x-data="{ open: false, scrolled: false, userMenu: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })">

    <header class="hidden md:flex fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b bg-(--background)"
        :class="scrolled ? 'border-(--background-3) shadow-sm' : 'border-transparent'">
        <div class="max-w-6xl w-full mx-auto px-8 flex items-center justify-between gap-8" style="height:72px">

            <div class="flex items-center gap-6">
                <a href="/" class="text-xl font-black tracking-wide text-(--text-primary)">
                    rent<span class="text-(--text-muted) font-normal">.use</span>
                    <span class="text-[11px] font-normal text-(--text-muted) ml-1">by Denis Beccev</span>
                </a>

                <div class="w-px h-4 bg-(--background-3)"></div>

                <nav class="flex items-center gap-0.5">
                    <a href="/search"
                        class="px-3 py-1.5 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-md transition-all">
                        {{ __('messages.browse') }}
                    </a>
                    @auth
                        <a href="{{ route('listings.create') }}"
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

                    <div class="w-px h-4 bg-(--background-3)"></div>
                    <div class="relative">
                        <button @click="userMenu = !userMenu"
                            class="flex items-center gap-2.5 px-3 py-1.5 text-sm font-medium text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-sm transition-all cursor-pointer group">
                            <div
                                class="w-7 h-7 rounded-sm bg-(--background-3) flex items-center justify-center shrink-0 overflow-hidden shadow-sm group-hover:shadow-(--background-3)/20">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <x-heroicon-s-user class="w-4 h-4 text-(--text-primary)" />
                                @endif
                            </div>

                            <span class="truncate max-w-30">{{ Auth::user()->name }}</span>

                            <x-heroicon-o-chevron-down
                                class="w-3 h-3 opacity-50 group-hover:opacity-100 transition-opacity" />
                        </button>

                        <div x-show="userMenu" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2" @click.away="userMenu = false"
                            class="absolute right-0 top-full mt-2 w-52 rounded-sm border border-(--background-3) bg-(--background-2) shadow-xl z-50 overflow-hidden">

                            <div
                                class="absolute -top-1.5 right-4 w-3 h-3 bg-(--background-2) border-l border-t border-(--background-3) rotate-45">
                            </div>

                            <div class="p-3 border-b border-(--background-3)">
                                <p class="text-xs font-semibold text-(--text-primary)">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-(--text-muted) truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="p-1.5">
                                <a href="/profile"
                                    class="flex items-center gap-2 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm transition-all">
                                    <x-heroicon-o-user class="w-4 h-4 shrink-0" />
                                    Profile
                                </a>
                                <a href="{{ route('listings.create') }}" @click="open = false"
                                    class="block px-2 py-2.5 rounded-md text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                                    {{ __('messages.post') }}
                                </a>
                            </div>

                            <div class="p-1.5 border-t border-(--background-3)">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-(--background-3) rounded-sm transition-all cursor-pointer">
                                        <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 shrink-0" />
                                        {{ __('messages.sign-out') }}
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @endauth

                <div class="flex items-center rounded-sm p-0.5 text-xs font-medium bg-(--background-2)">
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="px-3 py-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)/30 transition-all">EN</a>
                    <a href="{{ route('lang.switch', 'ro') }}"
                        class="px-3 py-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)/30 transition-all">MD</a>
                </div>

            </div>
        </div>
    </header>

    <div class="hidden md:block" style="height:72px"></div>

    <header class="md:hidden fixed top-0 left-0 right-0 z-50 border-b border-(--background-2) bg-(--background)">
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
            class="border-t border-(--background-2) px-4 pt-3 pb-5 space-y-1 bg-(--background)">

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
                <a href="{{ route('listings.create') }}" @click="userMenu = false"
                    class="flex items-center gap-2 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm transition-all">
                    <x-heroicon-o-plus class="w-4 h-4 shrink-0" />
                    {{ __('messages.post') }}
                </a>
                <a href="/profile" @click="open = false"
                    class="block px-2 py-2.5 rounded-md text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                    {{ Auth::user()->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-2 py-2.5 rounded-md text-sm text-red-400 hover:text-red-300 hover:bg-(--background-2) transition-all cursor-pointer">
                        {{ __('messages.sign-out') }}
                    </button>
                </form>
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
                <a href="{{ route('lang.switch', 'md') }}"
                    class="px-3 py-1.5 rounded-sm text-xs font-medium text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">MD</a>
            </div>

        </div>
    </header>

    <div class="md:hidden" style="height:52px"></div>
</div>
