<?php

use Livewire\Component;

new class extends Component {}; ?>

<div x-data="{ open: false, scrolled: false, userMenu: false, logoutModal: false }" x-cloak>
    <header
        class="headroom hidden lg:flex fixed top-0 left-0 right-0 z-50 bg-(--background) border-b border-(--background-3) flex-col">

        <div class="w-full bg-(--blackwhite) border-b border-(--background-3)">
            <div class="max-w-6xl mx-auto px-6 h-10 flex items-center">
                <a href="/" class="text-sm font-black tracking-wide text-(--whiteblack)">
                    rent<span class="text-(--whiteblack) font-normal">.use</span>

                    <span class="text-[10px] font-normal text---whiteblack) ml-1">
                        by Denis Beccev
                    </span>
                </a>
            </div>
        </div>

        <div class="max-w-6xl w-full mx-auto px-3 flex items-center justify-between gap-8" style="height:72px">

            {{-- LEFT --}}
            <div class="flex items-center gap-4">
                <a href="/search"
                    class="shrink-0 px-3 py-1.5 text-sm font-bold text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-sm">
                    {{ __('messages.browse') }}
                </a>
                @auth
                    <a href="{{ route('listings.create') }}"
                        class="shrink-0 px-3 py-1.5 text-sm font-bold text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-sm">
                        {{ __('messages.post') }}
                    </a>

                    @if (auth()->user()?->is_admin)
                        <a href="/admin"
                            class="shrink-0 px-3 py-1.5 text-sm font-bold text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) rounded-sm">
                            Admin Panel
                        </a>
                    @endif
                @endauth


                <form method="GET" action="{{ route('search') }}" class="w-full max-w-sm">
                    <div
                        class="group flex items-center gap-2 h-10 px-3 rounded-md bg-(--background-2)/80 border border-(--background-3) backdrop-blur-md transition-all duration-200 hover:border-(--text-muted)/40">
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="{{ __('messages.search') }}" autocomplete="off"
                            class="w-full bg-transparent border-0 outline-none text-sm text-(--text-primary) placeholder:text-(--blackwhite)/70 focus:ring-0">

                        <x-heroicon-o-magnifying-glass
                            class="w-4 h-4 text-(--text-muted) group-focus-within:text-(--button) shrink-0 transition-colors" />

                        @if (request('q'))
                            <a href="{{ route('search') }}"
                                class="text-(--text-muted) hover:text-(--text-primary) transition-colors">
                                <x-heroicon-o-x-mark class="w-4 h-4" />
                            </a>
                        @endif
                    </div>
                </form>
                </nav>
            </div>

            {{-- RIGHT --}}
            <div class="flex items-center gap-2">

                @guest
                    <a href="/login"
                        class="px-4 py-1.5 rounded-sm bg-(--button) text-(--button-text) text-sm font-medium hover:bg-(--button-h) cursor-pointer">
                        {{ __('messages.started') }}
                    </a>
                @endguest

                @auth
                    <button class="p-1.5 rounded-sm text-(--text-btn-header) hover:text-(--button-h) cursor-pointer">
                        <x-heroicon-o-heart class="w-5 h-5" />
                    </button>
                    <button class="p-1.5 rounded-sm text-(--text-btn-header) hover:text-(--button-h) cursor-pointer">
                        <x-heroicon-o-chat-bubble-bottom-center class="w-5 h-5" />
                    </button>
                    <button class="p-1.5 rounded-sm text-(--text-btn-header) hover:text-(--button-h) cursor-pointer">
                        <x-heroicon-o-calendar class="w-5 h-5" />
                    </button>

                    <div class="w-px h-4 bg-(--background-3) mx-1"></div>

                    <div class="relative">
                        <button @click="userMenu = !userMenu"
                            class="flex items-center gap-1.5 p-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) cursor-pointer group">
                            <div
                                class="w-6 h-6 rounded-md bg-(--background-3) border-(--button-h) flex items-center justify-center shrink-0 overflow-hidden">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-[10px] font-medium text-(--text-muted) uppercase leading-none">
                                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <x-heroicon-o-chevron-down class="w-3 h-3 opacity-30 group-hover:opacity-60" />
                        </button>

                        <div x-show="userMenu" x-cloak @click.away="userMenu = false"
                            class="absolute right-0 top-full mt-2 w-52 rounded-sm border border-(--background-3) bg-(--background-2) shadow-xl z-50 overflow-hidden">

                            <div
                                class="absolute -top-1.5 right-4 w-3 h-3 bg-(--background-2) border-l border-t border-(--background-3) rotate-45">
                            </div>

                            <div class="p-3 border-b border-(--background-3) bg-(--background-1)/50">
                                <p class="text-xs font-semibold text-(--text-primary)">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-(--text-muted) truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="p-1.5 flex flex-col gap-0.5">
                                <a href="/profile"
                                    class="flex items-center gap-2.5 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm group">
                                    <x-heroicon-o-user class="w-4 h-4 shrink-0 opacity-70 group-hover:opacity-100" />
                                    <span>Profile</span>
                                </a>
                                <a href="{{ route('listings.create') }}" @click="userMenu = false"
                                    class="flex items-center gap-2.5 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm group">
                                    <x-heroicon-o-plus class="w-4 h-4 shrink-0 opacity-70 group-hover:opacity-100" />
                                    <span>{{ __('messages.post') }}</span>
                                </a>
                            </div>

                            <div class="p-1.5 border-t border-(--background-3)">
                                <button type="button" @click="logoutModal = true; userMenu = false"
                                    class="flex items-center gap-2.5 w-full px-3 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-sm cursor-pointer">
                                    <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 shrink-0" />
                                    {{ __('messages.sign-out') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endauth

                <div class="w-px h-4 bg-(--background-3) mx-1"></div>

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
                        class="p-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) cursor-pointer">
                        <template x-if="!isDark">
                            <x-heroicon-o-moon class="w-4 h-4" />
                        </template>
                        <template x-if="isDark">
                            <x-heroicon-o-sun class="w-4 h-4" />
                        </template>
                    </button>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="px-2 py-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) cursor-pointer text-xs font-bold uppercase">
                        {{ app()->getLocale() == 'ro' ? 'RO' : strtoupper(app()->getLocale()) }}
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                        class="absolute right-0 mt-2 w-32 bg-(--background-2) border border-(--background-3) rounded-sm shadow-xl z-50 overflow-hidden">
                        <div class="flex flex-col p-1">
                            <a href="{{ route('lang.switch', 'en') }}"
                                class="flex items-center justify-between px-3 py-2 rounded-sm text-sm {{ app()->getLocale() == 'en' ? 'text-(--text-primary) font-semibold' : 'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)' }}">
                                English
                                @if (app()->getLocale() == 'en')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                            <a href="{{ route('lang.switch', 'ro') }}"
                                class="flex items-center justify-between px-3 py-2 rounded-sm text-sm {{ app()->getLocale() == 'ro' ? 'text-(--text-primary) font-semibold' : 'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)' }}">
                                Română
                                @if (app()->getLocale() == 'ro')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                            <a href="{{ route('lang.switch', 'ru') }}"
                                class="flex items-center justify-between px-3 py-2 rounded-sm text-sm {{ app()->getLocale() == 'ru' ? 'text-(--text-primary) font-semibold' : 'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)' }}">
                                Русский
                                @if (app()->getLocale() == 'ru')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <div class="hidden md:block" style="height:72px"></div>

    {{-- MOBILE HEADER --}}
    <header class="lg:hidden fixed top-0 left-0 right-0 z-50 border-b border-(--background-2) bg-(--background)">
        <div class="flex items-center justify-between p-4">
            <a href="/" class="text-xs md:text-lg font-black tracking-wide text-(--text-primary)">
                rent<span class="text-(--text-muted) font-normal">.use</span>
            </a>
            <button @click="open = !open"
                class="text-xs md:text-lg font-medium text-(--text-muted) hover:text-(--text-primary) cursor-pointer">
                <span x-show="!open">Menu</span>
                <span x-show="open" x-cloak>{{ __('messages.close') }}</span>
            </button>
        </div>
    </header>

    {{-- MOBILE MENU --}}
    <div x-show="open" x-cloak class="lg:hidden fixed inset-0 z-40 bg-(--background) flex flex-col"
        style="padding-top:52px">

        @auth
            <div class="px-5 py-4 border-b border-(--background-2) flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-sm bg-(--background-3) flex items-center justify-center shrink-0 overflow-hidden">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-sm font-medium text-(--text-muted) uppercase leading-none">
                            {{ mb_substr(Auth::user()->name, 0, 1) }}
                        </span>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-(--text-primary) truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-(--text-muted) truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        @endauth

        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">

            @auth
                <a href="/favorites" @click="open = false"
                    class="flex items-center gap-3 px-3 py-3 rounded-sm text-md text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2)">
                    <x-heroicon-o-heart class="w-4 h-4 shrink-0 opacity-60" />
                    {{ __('messages.favorite') }}
                </a>
                <a href="/messages" @click="open = false"
                    class="flex items-center gap-3 px-3 py-3 rounded-sm text-md text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2)">
                    <x-heroicon-o-chat-bubble-bottom-center class="w-4 h-4 shrink-0 opacity-60" />
                    {{ __('messages.messages') }}
                </a>
                <a href="/rented" @click="open = false"
                    class="flex items-center gap-3 px-3 py-3 rounded-sm text-md text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2)">
                    <x-heroicon-o-calendar class="w-4 h-4 shrink-0 opacity-60" />
                    {{ __('messages.rent') }}
                </a>

                <div class="w-full h-px bg-(--background-2) my-1"></div>
            @endauth

            <a href="/search" @click="open = false"
                class="flex items-center gap-3 px-3 py-3 rounded-sm text-md text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2)">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 shrink-0 opacity-60" />
                {{ __('messages.browse') }}
            </a>
            <a href="/" @click="open = false"
                class="flex items-center gap-3 px-3 py-3 rounded-sm text-md text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2)">
                <x-heroicon-o-lifebuoy class="w-4 h-4 shrink-0 opacity-60" />
                {{ __('messages.support') }}
            </a>
            <a href="/" @click="open = false"
                class="flex items-center gap-3 px-3 py-3 rounded-sm text-md text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2)">
                <x-heroicon-o-question-mark-circle class="w-4 h-4 shrink-0 opacity-60" />
                FAQ
            </a>

            @auth
                <div class="w-full h-px bg-(--background-2) my-1"></div>

                <a href="/profile" @click="open = false"
                    class="flex items-center gap-3 px-3 py-3 rounded-sm text-md text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2)">
                    <x-heroicon-o-user class="w-4 h-4 shrink-0 opacity-60" />
                    Profile
                </a>
                <a href="{{ route('listings.create') }}" @click="open = false"
                    class="flex items-center gap-3 px-3 py-3 rounded-sm text-md text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2)">
                    <x-heroicon-o-plus class="w-4 h-4 shrink-0 opacity-60" />
                    {{ __('messages.post') }}
                </a>
            @endauth

        </nav>

        <div class="px-3 pb-6 pt-2 border-t border-(--background-2) space-y-3">
            <div class="flex items-center justify-between px-1">

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-sm bg-(--background-2) border border-(--background-3) text-xs font-bold uppercase text-(--text-muted) hover:text-(--text-primary) cursor-pointer">
                        <x-heroicon-o-globe-alt class="w-4 h-4" />
                        {{ app()->getLocale() == 'ro' ? 'RO' : strtoupper(app()->getLocale()) }}
                        <x-heroicon-o-chevron-up class="w-3 h-3 opacity-50" />
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false"
                        class="absolute left-0 bottom-full mb-2 w-36 bg-(--background-2) border border-(--background-3) rounded-sm shadow-xl z-50 overflow-hidden">
                        <div class="flex flex-col p-1">
                            <a href="{{ route('lang.switch', 'en') }}"
                                class="flex items-center justify-between px-3 py-2 rounded-sm text-sm {{ app()->getLocale() == 'en' ? 'text-(--text-primary) font-semibold bg-(--background-3)' : 'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)' }}">
                                English
                                @if (app()->getLocale() == 'en')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                            <a href="{{ route('lang.switch', 'ro') }}"
                                class="flex items-center justify-between px-3 py-2 rounded-sm text-sm {{ app()->getLocale() == 'ro' ? 'text-(--text-primary) font-semibold bg-(--background-3)' : 'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)' }}">
                                Română
                                @if (app()->getLocale() == 'ro')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                            <a href="{{ route('lang.switch', 'ru') }}"
                                class="flex items-center justify-between px-3 py-2 rounded-sm text-sm {{ app()->getLocale() == 'ru' ? 'text-(--text-primary) font-semibold bg-(--background-3)' : 'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)' }}">
                                Русский
                                @if (app()->getLocale() == 'ru')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

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
                        class="flex items-center gap-2 px-3 py-2.5 rounded-sm bg-(--background-2) border border-(--background-3) text-xs font-medium text-(--text-muted)     cursor-pointer">
                        <template x-if="!isDark">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-moon class="w-4 h-4" />
                                <span>{{ __('messages.dark-mode') }}</span>
                            </div>
                        </template>
                        <template x-if="isDark">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-sun class="w-4 h-4" />
                                <span>{{ __('messages.light-mode') }}</span>
                            </div>
                        </template>
                    </button>
                </div>
            </div>

            @guest
                <a href="/login"
                    class="flex items-center justify-center w-full px-4 py-2.5 rounded-sm bg-(--button) text-(--button-text) text-sm font-medium hover:bg-(--button-h) active:scale-95">
                    {{ __('messages.started') }}
                </a>
            @endguest

            @auth
                <button type="button" @click="logoutModal = true; open = false"
                    class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-sm text-sm text-red-400 hover:text-red-300 bg-red-400/5 hover:bg-red-400/10 cursor-pointer">
                    <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 shrink-0" />
                    {{ __('messages.sign-out') }}
                </button>
            @endauth
        </div>
    </div>

    <div class="md:hidden" style="height:52px"></div>

    {{-- LOGOUT MODAL --}}
    @auth
        <div x-show="logoutModal" x-cloak class="fixed inset-0 z-60 flex items-end sm:items-center justify-center p-4">

            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="logoutModal = false"></div>

            <div
                class="relative z-10 w-full sm:max-w-sm p-6 bg-(--background-2) border border-(--background-3) rounded-sm shadow-2xl">
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="w-10 h-10 rounded-sm bg-red-500/10 flex items-center justify-center shrink-0 border border-red-500/10">
                        <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 text-red-500" />
                    </div>
                    <h2 class="text-base font-bold text-(--text-primary) leading-tight">
                        {{ __('messages.sign-out-confirm-title') }}
                    </h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" @click="logoutModal = false"
                        class="flex-1 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm cursor-pointer">
                        {{ __('messages.back') }}
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full px-3 py-2 text-sm text-red-400 hover:text-red-300 bg-red-400/5 hover:bg-red-400/10 rounded-sm cursor-pointer">
                            {{ __('messages.sign-out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth
</div>

{{-- Headroom --}}

<script>
    const header = document.querySelector('header.headroom');
    if (header) {
        const headroom = new Headroom(header, {
            offset: 80,
            tolerance: {
                up: 5,
                down: 5
            },
        });
        headroom.init();
    }
</script>
