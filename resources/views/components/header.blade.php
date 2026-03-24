<?php

use Livewire\Component;

new class extends Component {};
?>

<div x-data="{ open: false, scrolled: false, userMenu: false, logoutModal: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })" x-cloak>

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
                            class="absolute -right-25 top-full mt-2 w-52 rounded-sm border border-(--background-3) bg-(--background-2) shadow-xl z-50 overflow-hidden">

                            <div
                                class="absolute -top-1.5 right-4 w-3 h-3 bg-(--background-2) border-l border-t border-(--background-3) rotate-45">
                            </div>

                            <div class="p-3 border-b border-(--background-3) bg-(--background-1)/50">
                                <p class="text-xs font-semibold text-(--text-primary)">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-(--text-muted) truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="p-1.5 flex flex-col gap-0.5">
                                <a href="/profile"
                                    class="flex items-center gap-2.5 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm transition-all group">
                                    <x-heroicon-o-user class="w-4 h-4 shrink-0 opacity-70 group-hover:opacity-100" />
                                    <span>Profile</span>
                                </a>
                                <a href="{{ route('listings.create') }}" @click="userMenu = false"
                                    class="flex items-center gap-2.5 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm transition-all group">
                                    <x-heroicon-o-plus class="w-4 h-4 shrink-0 opacity-70 group-hover:opacity-100" />
                                    <span>{{ __('messages.post') }}</span>
                                </a>

                                <div x-data="{
                                    isDark: document.documentElement.classList.contains('dark'),
                                    toggle() {
                                        this.isDark = !this.isDark;
                                        if (this.isDark) {
                                            document.documentElement.classList.add('dark');
                                            localStorage.setItem('theme', 'dark');
                                        } else {
                                            document.documentElement.classList.remove('dark');
                                            localStorage.setItem('theme', 'light');
                                        }
                                    }
                                }">
                                    <button @click="toggle()"
                                        class="flex items-center gap-2.5 w-full px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm transition-all group cursor-pointer">

                                        <template x-if="!isDark">
                                            <div class="flex items-center gap-2.5">
                                                <x-heroicon-o-moon
                                                    class="w-4 h-4 shrink-0 opacity-70 group-hover:opacity-100" />
                                                <span>{{ __('messages.dark-mode') }}</span>
                                            </div>
                                        </template>

                                        <template x-if="isDark">
                                            <div class="flex items-center gap-2.5">
                                                <x-heroicon-o-sun class="w-4 h-4 shrink-0 opacity-100" />
                                                <span>{{ __('messages.light-mode') }}</span>
                                            </div>
                                        </template>
                                    </button>
                                </div>
                            </div>

                            <div class="p-1.5 border-t border-(--background-3)">
                                <button type="button" @click="logoutModal = true; userMenu = false"
                                    class="flex items-center gap-2.5 w-full px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-sm transition-all cursor-pointer">
                                    <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 shrink-0" />
                                    {{ __('messages.sign-out') }}
                                </button>
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
    </header>

    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-full"
        class="md:hidden fixed inset-0 z-40 bg-(--background) flex flex-col" style="padding-top:52px">

        @auth
            <div class="px-5 py-4 border-b border-(--background-2) flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-sm bg-(--background-3) flex items-center justify-center shrink-0 overflow-hidden">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <x-heroicon-s-user class="w-5 h-5 text-(--text-primary)" />
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-(--text-primary) truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-(--text-muted) truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        @endauth

        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
            <a href="/search" @click="open = false"
                class="flex items-center gap-3 px-3 py-3 rounded-sm text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 shrink-0 opacity-60" />
                {{ __('messages.browse') }}
            </a>
            <a href="/" @click="open = false"
                class="flex items-center gap-3 px-3 py-3 rounded-sm text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                <x-heroicon-o-lifebuoy class="w-4 h-4 shrink-0 opacity-60" />
                Support
            </a>
            <a href="/" @click="open = false"
                class="flex items-center gap-3 px-3 py-3 rounded-sm text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                <x-heroicon-o-question-mark-circle class="w-4 h-4 shrink-0 opacity-60" />
                FAQ
            </a>

            @auth
                <div class="pt-1 mt-1 border-t border-(--background-2) space-y-0.5">
                    <a href="/profile" @click="open = false"
                        class="flex items-center gap-3 px-3 py-3 rounded-sm text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                        <x-heroicon-o-user class="w-4 h-4 shrink-0 opacity-60" />
                        Profile
                    </a>
                    <a href="{{ route('listings.create') }}" @click="open = false"
                        class="flex items-center gap-3 px-3 py-3 rounded-sm text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) transition-all">
                        <x-heroicon-o-plus class="w-4 h-4 shrink-0 opacity-60" />
                        {{ __('messages.post') }}
                    </a>
                </div>
            @endauth
        </nav>

        <div class="px-3 pb-6 pt-2 border-t border-(--background-2) space-y-3">

            <div class="flex items-center justify-between px-1">
                <div class="flex items-center rounded-sm p-0.5 text-xs font-medium bg-(--background-2)">
                    <button @click="window.location.href='{{ route('lang.switch', 'en') }}'"
                        class="px-3 py-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)/30 transition-all">EN</button>
                    <button @click="window.location.href='{{ route('lang.switch', 'ro') }}'"
                        class="px-3 py-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)/30 transition-all">MD</button>
                </div>
                <button onclick="toggleTheme()"
                    class="p-2 text-(--text-muted) hover:text-(--text-primary) transition-colors">
                    <x-heroicon-o-sun class="h-5 w-5 hidden dark:block" />
                    <x-heroicon-o-moon class="h-5 w-5 dark:hidden" />
                </button>
            </div>

            @guest
                <a href="/login"
                    class="flex items-center justify-center w-full px-4 py-2.5 rounded-sm bg-(--button) text-(--button-text) text-sm font-medium hover:bg-(--button-h) transition-all active:scale-95">
                    {{ __('messages.started') }}
                </a>
            @endguest

            @auth
                <button type="button" @click="logoutModal = true; open = false"
                    class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-sm text-sm text-red-400 hover:text-red-300 bg-red-400/5 hover:bg-red-400/10 transition-all cursor-pointer">
                    <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 shrink-0" />
                    {{ __('messages.sign-out') }}
                </button>
            @endauth
        </div>
    </div>

    <div class="md:hidden" style="height:52px"></div>

    @auth
        <div x-show="logoutModal" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-60 flex items-end sm:items-center justify-center p-4">

            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="logoutModal = false"></div>

            <div x-show="logoutModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                class="relative z-10 w-full sm:max-w-sm p-6 bg-(--background-2) border border-(--background-3) rounded-lg shadow-2xl">

                <div class="flex items-start gap-4 mb-5">
                    <div class="w-9 h-9 rounded-sm bg-red-400/10 flex items-center justify-center shrink-0">
                        <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 text-red-400" />
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-(--text-primary)">
                            {{ __('messages.sign-out-confirm-title') }}
                        </h2>
                        <p class="mt-0.5 text-sm text-(--text-muted)">
                            {{ __('messages.sign-out-confirm-description') }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="button" @click="logoutModal = false"
                        class="flex-1 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm transition-all cursor-pointer">
                        {{ __('messages.cancel') }}
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-3 py-2 text-sm text-red-400 hover:text-red-300 bg-red-400/5 hover:bg-red-400/10 rounded-sm transition-all cursor-pointer">
                            {{ __('messages.sign-out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth

</div>
