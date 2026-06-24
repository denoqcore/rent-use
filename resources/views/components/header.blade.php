<?php

use Livewire\Component;

new class extends Component {}; ?>

<div x-cloak>
    <header
        class="headroom hidden lg:flex fixed top-0 left-0 right-0 z-50 bg-(--background) border-b border-(--background-3) flex-col">

        <div class="w-full border-b border-(--background-3) bg-(--blackwhite)">
            <div class="max-w-6xl mx-auto h-12 px-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="/" class="flex items-center group">
                        <span class="text-base font-black tracking-wide text-(--whiteblack)">
                            rent<span class="font-normal">.use</span>
                        </span>
                    </a>
                </div>
                <p class="hidden md:block text-[11px] leading-tight text-(--whiteblack)/45 text-right max-w-lg">
                    {{ __('messages.portfolio_disclaimer') }}
                </p>
                <a href="https://github.com/markwellq" target="_blank"
                    class="hidden sm:flex items-center gap-1.5 text-(--whiteblack)/60 hover:text-(--button) transition-colors duration-200">
                    <span class="text-[11px]">by Denis Beccev</span>
                    <img src="{{ asset('storage/images/github.svg') }}" alt="GitHub"
                        class="w-3 h-3 bg-(--whiteblack) rounded-full">
                </a>
            </div>
        </div>

        <div class="max-w-6xl w-full mx-auto px-3 flex items-center justify-between gap-8" style="height:72px">
            <div class="flex items-center gap-4">
                <a href="/" class="rounded-xl border-b border-transparent text-(--text-muted) ml-2">
                    {{-- <img src="{{ asset('storage/images/logo.svg') }}" alt="rent.use" class="w-14 h-8"> --}}
                    <x-heroicon-s-stop-circle class="w-5 h-5" />
                </a>
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
                    <button @click="favoritesModal = true"
                        class="p-1.5 rounded-sm text-(--text-btn-header) hover:text-(--button-h) cursor-pointer">
                        <x-heroicon-o-heart class="w-5 h-5" />
                    </button>
                    <button @click="openChats()"
                        class="relative p-1.5 rounded-sm text-(--text-btn-header) hover:text-(--button-h) cursor-pointer">
                        <x-heroicon-o-chat-bubble-bottom-center class="w-5 h-5" />
                        <span x-show="unreadTotal > 0" x-text="unreadTotal"
                            class="absolute -top-0.5 -right-0.5 w-4 h-4 text-[10px] font-bold flex items-center justify-center rounded-full"
                            style="background: var(--button); color: var(--button-text)">
                        </span>
                    </button>
                    <button @click="bookingsModal = true; pendingBookings = 0; loadBookings()"
                        class="relative p-1.5 rounded-sm text-(--text-btn-header) hover:text-(--button-h) cursor-pointer">
                        <x-heroicon-o-calendar class="w-5 h-5" />
                        <span x-show="pendingBookings > 0" x-text="pendingBookings"
                            class="absolute -top-0.5 -right-0.5 w-4 h-4 text-[10px] font-bold flex items-center justify-center rounded-full"
                            style="background: var(--button); color: var(--button-text)">
                        </span>
                    </button>

                    <div class="w-px h-4 bg-(--background-3) mx-1"></div>

                    <div class="relative">
                        <button @click="userMenu = !userMenu"
                            class="flex items-center gap-1.5 p-1.5 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) cursor-pointer group">
                            <div
                                class="w-6 h-6 rounded-md bg-(--background-3) flex items-center justify-center shrink-0 overflow-hidden">
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

                            <div class="relative p-3 border-b border-(--background-3)">
                                <p class="text-xs font-semibold text-(--text-primary) truncate pr-16">
                                    {{ Auth::user()->name }}</p>
                                <p class="text-xs text-(--text-muted) truncate pr-16">{{ Auth::user()->email }}</p>

                                @if (Auth::user()->plan === 'premium')
                                    <span
                                        class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold bg-yellow-400/10 text-yellow-400 border border-yellow-400/20">
                                        PREMIUM
                                    </span>
                                @elseif (Auth::user()->plan === 'pro')
                                    <span
                                        class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold bg-blue-400/10 text-blue-400 border border-blue-400/20">
                                        PRO
                                    </span>
                                @else
                                    <span
                                        class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold bg-(--background-3) text-(--text-muted) border border-(--background-3)">
                                        STARTER
                                    </span>
                                @endif
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
                        <template x-if="!isDark"><x-heroicon-o-moon class="w-4 h-4" /></template>
                        <template x-if="isDark"><x-heroicon-o-sun class="w-4 h-4" /></template>
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
                                English @if (app()->getLocale() == 'en')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                            <a href="{{ route('lang.switch', 'ro') }}"
                                class="flex items-center justify-between px-3 py-2 rounded-sm text-sm {{ app()->getLocale() == 'ro' ? 'text-(--text-primary) font-semibold' : 'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)' }}">
                                Română @if (app()->getLocale() == 'ro')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                            <a href="{{ route('lang.switch', 'ru') }}"
                                class="flex items-center justify-between px-3 py-2 rounded-sm text-sm {{ app()->getLocale() == 'ru' ? 'text-(--text-primary) font-semibold' : 'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3)' }}">
                                Русский @if (app()->getLocale() == 'ru')
                                    <x-heroicon-o-check class="w-3.5 h-3.5" />
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- MOBILE HEADER --}}
    <header class="lg:hidden fixed top-0 left-0 right-0 z-50 border-b border-(--background-2) bg-(--background)">
        <div class="flex items-center justify-between px-4 h-14">
            <a href="/" class="flex items-center gap-1">
                <span class="text-sm font-black tracking-wide text-(--text-primary)">rent<span
                        class="text-(--text-muted) font-normal">.use</span></span>
            </a>
            <form method="GET" action="{{ route('search') }}" class="flex-1 mx-3">
                <div
                    class="flex items-center gap-2 h-9 px-3 rounded-lg bg-(--background-2) border border-(--background-3)">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="{{ __('messages.search') }}" autocomplete="off"
                        class="w-full bg-transparent border-0 outline-none text-sm text-(--text-primary) placeholder:text-(--text-muted)/70">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4 text-(--text-muted) shrink-0" />
                </div>
            </form>
        </div>
    </header>

    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-(--background) border-t border-(--background-3)"
        style="padding-bottom: env(safe-area-inset-bottom)">
        <div class="flex items-center justify-around h-16 px-2">

            @auth
                <button @click="bookingsModal = true; pendingBookings = 0; loadBookings()"
                    class="relative flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                    <x-heroicon-o-calendar class="w-5 h-5" />
                    <span class="text-[10px] font-medium">{{ __('messages.rent') }}</span>
                    <span x-show="pendingBookings > 0" x-text="pendingBookings"
                        class="absolute top-1 right-2 min-w-4 h-4 px-1 text-[10px] font-bold flex items-center justify-center rounded-full"
                        style="background: var(--button); color: var(--button-text)">
                    </span>
                </button>
            @else
                <a href="{{ route('login') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-(--text-muted)">
                    <x-heroicon-o-calendar class="w-5 h-5" />
                    <span class="text-[10px] font-medium">{{ __('messages.rent') }}</span>
                </a>
            @endauth

            @auth
                <button @click="favoritesModal = true"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                    <x-heroicon-o-heart class="w-5 h-5" />
                    <span class="text-[10px] font-medium">{{ __('messages.favorite') }}</span>
                </button>
            @else
                <a href="{{ route('login') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-(--text-muted)">
                    <x-heroicon-o-heart class="w-5 h-5" />
                    <span class="text-[10px] font-medium">{{ __('messages.favorite') }}</span>
                </a>
            @endauth

            @auth
                <a href="{{ route('listings.create') }}" class="flex flex-col items-center gap-1 cursor-pointer">
                    <div class="w-8 h-8 rounded-2xl flex items-center justify-center shadow-lg transition-colors"
                        style="background: var(--button)">
                        <x-heroicon-o-plus class="w-4 h-4 text-(--button-text)" />
                    </div>
                </a>
            @else
                <a href="{{ route('login') }}" class="flex flex-col items-center gap-1">
                    <div class="w-8 h-8 rounded-2xl flex items-center justify-center shadow-lg transition-colors"
                        style="background: var(--button)">
                        <x-heroicon-o-plus class="w-4 h-4 text-(--button-text)" />
                    </div>
                </a>
            @endauth

            @auth
                <button @click="openChats()"
                    class="relative flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                    <x-heroicon-o-chat-bubble-bottom-center class="w-5 h-5" />
                    <span class="text-[10px] font-medium">{{ __('messages.messages') }}</span>
                    <span x-show="unreadTotal > 0" x-text="unreadTotal"
                        class="absolute top-1 right-2 min-w-4 h-4 px-1 text-[10px] font-bold flex items-center justify-center rounded-full"
                        style="background: var(--button); color: var(--button-text)">
                    </span>
                </button>
            @else
                <a href="{{ route('login') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-(--text-muted)">
                    <x-heroicon-o-chat-bubble-bottom-center class="w-5 h-5" />
                    <span class="text-[10px] font-medium">{{ __('messages.messages') }}</span>
                </a>
            @endauth

            @auth
                @php $authUser = Auth::user(); @endphp
                <div class="relative">
                    <button @click="userMenu = !userMenu"
                        class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl cursor-pointer">
                        <div
                            class="w-7 h-7 rounded-full overflow-hidden bg-(--background-3) flex items-center justify-center">
                            @if ($authUser->avatar)
                                <img src="{{ asset('storage/' . $authUser->avatar) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-semibold text-(--text-muted) uppercase">
                                    {{ mb_substr($authUser->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] font-medium text-(--text-muted)">{{ __('messages.menu') }}</span>
                    </button>

                    <div x-show="userMenu" x-cloak @click.away="userMenu = false"
                        class="absolute bottom-full right-0 mb-2 w-52 rounded-xl border border-(--background-3) bg-(--background-2) shadow-2xl z-50 overflow-hidden">
                        <div class="p-3 border-b border-(--background-3)">
                            <p class="text-xs font-semibold text-(--text-primary)">{{ $authUser->name }}</p>
                            <p class="text-xs text-(--text-muted) truncate mb-2">{{ $authUser->email }}</p>

                            @if ($authUser->plan === 'premium')
                                <span
                                    class="inline-block px-2.5 py-1 rounded-sm text-xs font-bold bg-yellow-400/10 text-yellow-400 border border-yellow-400/20">
                                    PREMIUM
                                </span>
                            @elseif ($authUser->plan === 'pro')
                                <span
                                    class="inline-block px-2.5 py-1 rounded-sm text-xs font-bold bg-blue-400/10 text-blue-400 border border-blue-400/20">
                                    PRO
                                </span>
                            @else
                                <span
                                    class="inline-block px-2.5 py-1 rounded-sm text-xs font-bold bg-(--background-3) text-(--text-muted) border border-(--background-3)">
                                    STARTER
                                </span>
                            @endif
                        </div>
                        <div class="p-1.5 flex flex-col gap-0.5">
                            <a href="/profile"
                                class="flex items-center gap-2.5 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-lg">
                                <x-heroicon-o-user class="w-4 h-4 shrink-0" />
                                Profile
                            </a>
                            @if ($authUser->is_admin)
                                <a href="/admin"
                                    class="flex items-center gap-2.5 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-lg">
                                    <x-heroicon-o-wrench class="w-4 h-4 shrink-0" />
                                    Admin Panel
                                </a>
                            @endif
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
                                    class="flex items-center gap-2.5 w-full px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-lg cursor-pointer">
                                    <template x-if="!isDark"><x-heroicon-o-moon class="w-4 h-4 shrink-0" /></template>
                                    <template x-if="isDark"><x-heroicon-o-sun class="w-4 h-4 shrink-0" /></template>
                                    <span
                                        x-text="isDark ? '{{ __('messages.light-mode') }}' : '{{ __('messages.dark-mode') }}'"></span>
                                </button>
                            </div>
                            <div x-data="{ langOpen: false }" class="relative">
                                <button @click="langOpen = !langOpen"
                                    class="flex items-center gap-2.5 w-full px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-lg cursor-pointer">
                                    <x-heroicon-o-globe-alt class="w-4 h-4 shrink-0" />
                                    <span>{{ app()->getLocale() == 'ro' ? 'Română' : (app()->getLocale() == 'ru' ? 'Русский' : 'English') }}</span>
                                    <x-heroicon-o-chevron-right class="w-3 h-3 ml-auto" />
                                </button>
                                <div x-show="langOpen" x-cloak @click.away="langOpen = false"
                                    class="absolute bottom-0 right-full mr-1 w-36 bg-(--background-2) border border-(--background-3) rounded-xl shadow-xl overflow-hidden">
                                    <div class="flex flex-col p-1">
                                        <a href="{{ route('lang.switch', 'en') }}"
                                            class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ app()->getLocale() == 'en' ? 'text-(--text-primary) font-semibold' : 'text-(--text-muted) hover:bg-(--background-3)' }}">
                                            English @if (app()->getLocale() == 'en')
                                                <x-heroicon-o-check class="w-3.5 h-3.5" />
                                            @endif
                                        </a>
                                        <a href="{{ route('lang.switch', 'ro') }}"
                                            class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ app()->getLocale() == 'ro' ? 'text-(--text-primary) font-semibold' : 'text-(--text-muted) hover:bg-(--background-3)' }}">
                                            Română @if (app()->getLocale() == 'ro')
                                                <x-heroicon-o-check class="w-3.5 h-3.5" />
                                            @endif
                                        </a>
                                        <a href="{{ route('lang.switch', 'ru') }}"
                                            class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ app()->getLocale() == 'ru' ? 'text-(--text-primary) font-semibold' : 'text-(--text-muted) hover:bg-(--background-3)' }}">
                                            Русский @if (app()->getLocale() == 'ru')
                                                <x-heroicon-o-check class="w-3.5 h-3.5" />
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-1.5 border-t border-(--background-3)">
                            <button type="button" @click="logoutModal = true; userMenu = false"
                                class="flex items-center gap-2.5 w-full px-3 py-2 text-sm text-red-400 hover:bg-red-400/10 rounded-lg cursor-pointer">
                                <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 shrink-0" />
                                {{ __('messages.sign-out') }}
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-(--text-muted)">
                    <x-heroicon-o-user class="w-5 h-5" />
                    <span class="text-[10px] font-medium">{{ __('messages.login') }}</span>
                </a>
            @endauth

        </div>
    </div>


    {{-- MODAL WINDOWS --}}
    @auth
        <div x-show="favoritesModal" x-cloak class="fixed inset-0 z-60 flex justify-end" role="dialog"
            aria-modal="true">
            <div x-show="favoritesModal" x-transition:enter="ease-in-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="favoritesModal = false"
                class="absolute inset-0 bg-black/30 transition-opacity">
            </div>
            <div x-show="favoritesModal" x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                @keydown.escape.window="favoritesModal = false"
                class="relative z-10 w-screen max-w-md flex flex-col bg-(--background-2) border-l border-(--background-3) shadow-2xl h-full">

                <div class="flex items-center justify-between px-5 py-4 border-b border-(--background-3) shrink-0">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-heart class="w-4 h-4 text-(--text-muted)" />
                        <h2 class="text-sm font-bold text-(--text-primary)">{{ __('messages.favorite') }}</h2>
                    </div>
                    <button @click="favoritesModal = false"
                        class="p-1 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) cursor-pointer">
                        <x-heroicon-o-x-mark class="w-4 h-4" />
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-4">
                    @php
                        $userFavorites = auth()
                            ->user()
                            ->favoriteListings()
                            ->with([
                                'images' => fn($q) => $q->where('is_main', true)->orWhere('order', 0),
                                'city',
                                'category',
                            ])
                            ->latest('favorites.created_at')
                            ->get();
                    @endphp

                    @if ($userFavorites->isEmpty())
                        <div class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                            <x-heroicon-o-heart class="w-10 h-10 text-(--text-muted) opacity-30" />
                            <p class="text-sm font-medium text-(--text-primary)">{{ __('messages.no_favorites_title') }}
                            </p>
                            <p class="text-xs text-(--text-muted) max-w-xs">{{ __('messages.no_favorites_desc') }}</p>
                            <a href="{{ route('search') }}" @click="favoritesModal = false"
                                class="mt-2 px-4 py-2 text-xs font-medium bg-(--button) text-(--button-text) hover:bg-(--button-h) rounded-sm">
                                {{ __('messages.browse') }}
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col gap-2">
                            @foreach ($userFavorites as $fav)
                                <div
                                    class="relative flex items-center gap-3 p-2 rounded-sm transition-colors group hover:bg-(--background-3)
                                     {{ $fav->trashed() || $fav->status !== 'active' ? 'opacity-60' : '' }}">

                                    @if ($fav->trashed() || $fav->status !== 'active')
                                        <div
                                            class="absolute inset-0 left-60 z-10 flex items-center justify-center rounded-sm">
                                            <span
                                                class="px-3 py-1 text-xs font-semibold text-white bg-black/70 rounded-sm">
                                                {{ $fav->trashed() ? 'Deleted' : 'Inactive' }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="shrink-0 w-16 h-14 rounded-sm overflow-hidden bg-(--background-3)">
                                        @if ($fav->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $fav->images->first()->path) }}"
                                                alt="{{ $fav->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <x-heroicon-o-photo class="w-5 h-5 text-(--text-muted) opacity-40" />
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        @if (!$fav->trashed() && $fav->status === 'active')
                                            <a href="{{ route('listings.show', $fav->slug) }}"
                                                @click="favoritesModal = false">
                                                <p
                                                    class="text-sm font-semibold text-(--text-primary) truncate hover:underline">
                                                    {{ $fav->title }}
                                                </p>
                                            </a>
                                        @else
                                            <p class="text-sm font-semibold text-(--text-primary) truncate">
                                                {{ $fav->title }}
                                            </p>
                                        @endif

                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="flex items-center gap-1 text-[11px] text-(--text-muted)">
                                                <x-heroicon-s-map-pin class="w-2.5 h-2.5 shrink-0" />
                                                {{ $fav->city->name }}
                                            </span>
                                            <span class="text-[11px] font-bold text-(--button)">
                                                @if ($fav->price_per_day)
                                                    {{ number_format($fav->price_per_day, 0, '.', ' ') }}
                                                    {{ $fav->currency }}
                                                    <span class="text-(--text-muted) font-normal">/day</span>
                                                @elseif($fav->price_per_hour)
                                                    {{ number_format($fav->price_per_hour, 0, '.', ' ') }}
                                                    {{ $fav->currency }}
                                                    <span class="text-(--text-muted) font-normal">/hr</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('favorites.destroy', $fav) }}"
                                        class="shrink-0 relative z-20">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-sm text-(--text-muted) hover:text-red-400 hover:bg-red-400/10 cursor-pointer">
                                            <x-heroicon-o-x-mark class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div x-show="bookingsModal" x-cloak class="fixed inset-0 z-60 flex justify-end" role="dialog"
            aria-modal="true">
            <div x-show="bookingsModal" x-transition:enter="ease-in-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="bookingsModal = false"
                class="absolute inset-0 bg-black/30 transition-opacity">
            </div>
            <div x-show="bookingsModal" x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                @keydown.escape.window="bookingsModal = false"
                class="relative z-10 w-screen max-w-lg flex flex-col bg-(--background-2) border-l border-(--background-3) shadow-2xl h-full">

                <div class="flex items-center justify-between px-5 py-4 border-b border-(--background-3) shrink-0">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-calendar class="w-4 h-4 text-(--text-muted)" />
                        <h2 class="text-sm font-bold text-(--text-primary)">{{ __('messages.rent') }}</h2>
                    </div>
                    <button @click="bookingsModal = false"
                        class="p-1 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) cursor-pointer">
                        <x-heroicon-o-x-mark class="w-4 h-4" />
                    </button>
                </div>

                <div class="flex border-b border-(--background-3) shrink-0">
                    <button @click="bookingsTab = 'renter'"
                        :class="bookingsTab === 'renter' ? 'border-b-2 border-(--button) text-(--text-primary)' :
                            'text-(--text-muted) hover:text-(--text-primary)'"
                        class="flex-1 px-4 py-3 text-xs font-semibold transition-colors">
                        {{ __('messages.my_rentals') }}
                    </button>
                    <button @click="bookingsTab = 'owner'"
                        :class="bookingsTab === 'owner' ? 'border-b-2 border-(--button) text-(--text-primary)' :
                            'text-(--text-muted) hover:text-(--text-primary)'"
                        class="flex-1 px-4 py-3 text-xs font-semibold transition-colors relative">
                        {{ __('messages.incoming_requests') }}
                        <span x-show="incomingRequests.filter(b => b.status === 'pending').length > 0"
                            x-text="incomingRequests.filter(b => b.status === 'pending').length"
                            class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold bg-(--button) text-(--button-text) rounded-full">
                        </span>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-4">
                    <div x-show="bookingsLoading" class="flex items-center justify-center py-16">
                        <div class="w-5 h-5 border-2 border-t-transparent rounded-full animate-spin"
                            style="border-color: var(--button); border-top-color: transparent"></div>
                    </div>
                    <div x-show="!bookingsLoading && bookingsTab === 'renter'">
                        <div x-show="myRentals.length === 0"
                            class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                            <x-heroicon-o-calendar class="w-10 h-10 text-(--text-muted) opacity-30" />
                            <p class="text-sm font-medium text-(--text-primary)">{{ __('messages.no_rentals_title') }}</p>
                            <p class="text-xs text-(--text-muted) max-w-xs">{{ __('messages.no_rentals_desc') }}</p>
                            <a href="{{ route('search') }}" @click="bookingsModal = false"
                                class="mt-2 px-4 py-2 text-xs font-medium bg-(--button) text-(--button-text) hover:bg-(--button-h) rounded-sm">
                                {{ __('messages.browse') }}
                            </a>
                        </div>
                        <div x-show="myRentals.length > 0" class="flex flex-col gap-2">
                            <template x-for="booking in myRentals" :key="booking.id">
                                <div
                                    class="p-3 rounded-sm border border-(--background-3) bg-(--background) hover:border-(--button)/30 transition">
                                    <a :href="'/listings/' + booking.listing.slug" @click="bookingsModal = false"
                                        class="flex gap-3 group">
                                        <div class="shrink-0 w-34 h-30 rounded-sm overflow-hidden bg-(--background-3)">
                                            <template x-if="booking.listing.image">
                                                <img :src="'/storage/' + booking.listing.image"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                            </template>
                                            <template x-if="!booking.listing.image">
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <x-heroicon-o-photo class="w-5 h-5 text-(--text-muted)" />
                                                </div>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                                            <div>
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-semibold text-(--text-primary) truncate group-hover:underline"
                                                            x-text="booking.listing.title"></p>
                                                        <p class="text-xs text-(--text-muted)"
                                                            x-text="booking.listing.city"></p>
                                                    </div>
                                                    <div class="text-right shrink-0">
                                                        <p class="text-sm font-bold text-(--text-price)"
                                                            x-text="new Intl.NumberFormat('de-DE').format(booking.total_price) + ' ' + booking.currency">
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-2 flex items-center gap-2 text-[11px] text-(--text-muted)">
                                                    <x-heroicon-o-calendar-days class="w-3 h-3" />
                                                    <span
                                                        x-text="booking.pricing_mode === 'hour'
                                                                ? booking.start_date + ' · ' + booking.start_hour + ' – ' + booking.end_hour
                                                                : booking.start_date + ' — ' + booking.end_date">
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-3 border-t border-(--background-3) flex items-center justify-between"
                                                @click.prevent>
                                                <span
                                                    class="px-2 py-0.5 rounded-sm text-sm md:text-xs font-medium capitalize"
                                                    :class="{
                                                        'bg-(--bg-status-warning) text-(--status-warning)': booking
                                                            .status === 'pending',
                                                        'bg-(--bg-status-ok) text-(--status-ok)': booking
                                                            .status === 'confirmed',
                                                        'bg-(--bg-status-danger) text-(--status-danger)': booking
                                                            .status === 'cancelled',
                                                        'bg-(--bg-status-muted) text-(--status-muted)': booking
                                                            .status === 'completed',
                                                    }"
                                                    x-text="{
                                                        pending: '{{ __('messages.status_pending') }}',
                                                        confirmed: '{{ __('messages.status_confirmed') }}',
                                                        cancelled: '{{ __('messages.status_cancelled') }}',
                                                        completed: '{{ __('messages.status_completed') }}'
                                                         }[booking.status] || booking.status">
                                                </span>
                                                <template x-if="booking.status === 'pending'">
                                                    <button @click.stop.prevent="cancelBooking(booking.id)"
                                                        class="text-md md:text-xs font-medium px-2 py-0.5 rounded-sm text-(--button-cancel) hover:underline hover:text-(--hvr-btn-cancel) transition cursor-pointer">
                                                        {{ __('messages.cancel') }}
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="!bookingsLoading && bookingsTab === 'owner'">
                        <div x-show="incomingRequests.length === 0"
                            class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                            <x-heroicon-o-inbox class="w-10 h-10 text-(--text-muted) opacity-30" />
                            <p class="text-sm font-medium text-(--text-primary)">{{ __('messages.no_requests_title') }}
                            </p>
                            <p class="text-xs text-(--text-muted) max-w-xs">{{ __('messages.no_requests_desc') }}</p>
                        </div>

                        <div x-show="incomingRequests.length > 0" class="flex flex-col gap-2">
                            <template x-for="booking in incomingRequests" :key="booking.id">
                                <div
                                    class="p-3 rounded-sm border border-(--background-3) bg-(--background) hover:border-(--button)/30 transition">
                                    <div class="flex gap-3">
                                        <div class="shrink-0 w-34 h-30 rounded-sm overflow-hidden bg-(--background-3)">
                                            <template x-if="booking.listing.image">
                                                <img :src="'/storage/' + booking.listing.image"
                                                    class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!booking.listing.image">
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <x-heroicon-o-photo class="w-5 h-5 text-(--text-muted)" />
                                                </div>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                                            <div>
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-semibold text-(--text-primary) truncate"
                                                            x-text="booking.listing.title"></p>
                                                        <p class="text-xs text-(--text-muted)"
                                                            x-text="booking.renter.name"></p>
                                                    </div>
                                                    <div class="text-right shrink-0">
                                                        <p class="text-sm font-bold text-(--text-price)"
                                                            x-text="new Intl.NumberFormat('de-DE').format(booking.total_price) + ' ' + booking.currency">
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mt-2 flex items-center gap-2 text-[11px] text-(--text-muted)">
                                                    <x-heroicon-o-calendar-days class="w-3 h-3" />
                                                    <span x-text="booking.start_date + ' — ' + booking.end_date"></span>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-3 border-t border-(--background-3) flex items-center justify-between"
                                                @click.stop.prevent>
                                                <template x-if="booking.status === 'pending'">
                                                    <div class="flex items-center justify-between w-full">
                                                        <span
                                                            class="px-2 py-0.5 rounded-sm text-xs font-medium capitalize bg-(--bg-status-warning) text-(--status-warning)"
                                                            x-text="booking.status">
                                                        </span>
                                                        <div class="flex gap-2">
                                                            <button @click="confirmBooking(booking.id)"
                                                                class="py-0.5 px-2 text-xs font-medium bg-green-500/10 text-green-500 hover:bg-green-500/20 rounded-sm cursor-pointer transition">
                                                                {{ __('messages.confirm') }}
                                                            </button>
                                                            <button @click="cancelBooking(booking.id)"
                                                                class="py-0.5 px-2 text-xs font-medium bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-sm cursor-pointer transition">
                                                                {{ __('messages.decline') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template x-if="booking.status !== 'pending'">
                                                    <div class="flex items-center justify-between w-full">
                                                        <span class="px-2 py-0.5 rounded-sm text-xs font-medium capitalize"
                                                            :class="{
                                                                'bg-(--bg-status-ok) text-(--status-ok)': booking
                                                                    .status === 'confirmed',
                                                                'bg-(--bg-status-danger) text-(--status-danger)': booking
                                                                    .status === 'cancelled',
                                                                'bg-(--bg-status-muted) text-(--status-muted)': booking
                                                                    .status === 'completed',
                                                            }"
                                                            x-text="{
                                                                confirmed: '{{ __('messages.status_confirmed') }}',
                                                                cancelled: '{{ __('messages.status_cancelled') }}',
                                                                completed: '{{ __('messages.status_completed') }}'
                                                            }[booking.status] || booking.status">
                                                        </span>
                                                        <template
                                                            x-if="booking.status === 'cancelled' || booking.status === 'confirmed'">
                                                            <button @click.stop.prevent="deleteBooking(booking.id)"
                                                                class="text-xs font-medium px-2 py-0.5 rounded-sm text-(--button-cancel) hover:underline hover:text-(--hvr-btn-cancel) transition cursor-pointer">
                                                                {{ __('messages.delete') }}
                                                            </button>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="chatsModal" x-cloak class="fixed inset-0 z-60 flex justify-end" role="dialog" aria-modal="true">
            <div x-show="chatsModal" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="chatsModal = false"
                class="absolute inset-0 bg-black/30 transition-opacity">
            </div>
            <div x-show="chatsModal" x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                @keydown.escape.window="chatsModal = false"
                class="relative z-10 w-screen max-w-md flex flex-col h-full shadow-2xl bg-(--background-2) border-l border-(--background-3)">

                <div class="flex items-center justify-between px-5 py-4 shrink-0 border-b border-(--background-3)">
                    <div class="flex items-center gap-2">
                        <button x-show="chatView === 'chat'" @click="chatView = 'list'; openChats()"
                            class="p-1 -ml-1 rounded-sm cursor-pointer hover:bg-(--background-3) mr-1 text-(--text-muted)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <x-heroicon-o-chat-bubble-bottom-center class="w-4 h-4 text-(--text-muted)" />
                        <h2 class="text-sm font-bold text-(--text-primary)">
                            <span x-show="chatView === 'list'">{{ __('messages.messages') }}</span>
                            <span x-show="chatView === 'chat'" x-text="activeChatData?.other_user?.name ?? ''"></span>
                        </h2>
                    </div>
                    <button @click="chatsModal = false"
                        class="p-1 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) cursor-pointer">
                        <x-heroicon-o-x-mark class="w-4 h-4" />
                    </button>
                </div>

                <div x-show="chatView === 'list'" class="flex-1 overflow-y-auto">
                    <div x-show="chatsLoading" class="flex items-center justify-center py-16">
                        <div class="w-5 h-5 border-2 border-t-transparent rounded-full animate-spin"
                            style="border-color: var(--button); border-top-color: transparent"></div>
                    </div>
                    <div x-show="!chatsLoading && chats.length === 0"
                        class="flex flex-col items-center justify-center gap-3 py-16 text-center px-6">
                        <x-heroicon-o-chat-bubble-bottom-center class="w-10 h-10 text-(--text-muted) opacity-20" />
                        <p class="text-sm font-medium text-(--text-primary)">{{ __('messages.status_messages') }}</p>
                        <p class="text-xs text-(--text-muted)">{{ __('messages.status_desc_messages') }}</p>
                    </div>
                    <div x-show="!chatsLoading && chats.length > 0" class="flex flex-col p-3 gap-1">
                        <template x-for="chat in chats" :key="chat.id">
                            <div @click="openChat(chat.id)"
                                class="flex items-center gap-3 p-3 rounded-sm cursor-pointer transition-colors hover:bg-(--background-3)">
                                <div class="w-10 h-10 rounded-full shrink-0 overflow-hidden flex items-center justify-center text-sm font-semibold"
                                    style="background: #dbeafe; color: var(--button)">
                                    <template x-if="chat.other_user.avatar">
                                        <img :src="'/storage/' + chat.other_user.avatar"
                                            class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!chat.other_user.avatar">
                                        <span x-text="chat.other_user.name.charAt(0).toUpperCase()"></span>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm font-semibold text-(--text-primary) truncate"
                                            x-text="chat.other_user.name"></p>
                                        <span class="text-[11px] text-(--text-muted) shrink-0"
                                            x-text="chat.last_message?.created_at ?? ''"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 mt-0.5">
                                        <p class="text-xs text-(--text-muted) truncate">
                                            <span x-show="chat.last_message?.is_mine">{{ __('messages.messages_you') }}:
                                            </span>
                                            <span x-text="chat.last_message?.body ?? chat.listing.title"></span>
                                        </p>
                                        <span x-show="chat.unread > 0" x-text="chat.unread"
                                            class="shrink-0 min-w-4 h-4 px-1 text-[10px] font-bold flex items-center justify-center rounded-full"
                                            style="background: var(--button); color: var(--button-text)">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="chatView === 'chat'" class="flex-1 flex flex-col min-h-0">
                    <div class="px-4 py-2 shrink-0 border-b border-(--background-3) bg-(--background)">
                        <p class="text-xs text-(--text-muted) truncate" x-text="activeChatData?.listing?.title ?? ''"></p>
                    </div>
                    <div id="chatScrollArea" class="flex-1 overflow-y-auto px-4 py-4 flex flex-col gap-2">
                        <template x-if="activeMessages.length === 0">
                            <div class="flex items-center justify-center h-full">
                                <div class="w-5 h-5 border-2 border-t-transparent rounded-full animate-spin"
                                    style="border-color: var(--button); border-top-color: transparent"></div>
                            </div>
                        </template>
                        <template x-for="msg in activeMessages" :key="msg.id">
                            <div :class="msg.is_mine ? 'items-end' : 'items-start'" class="flex flex-col gap-1">
                                <div class="max-w-[75%] px-3 py-2 rounded-2xl text-sm leading-relaxed wrap-break-word"
                                    :style="msg.is_mine ?
                                        'background: var(--button); color: var(--button-text); border-bottom-right-radius: 4px' :
                                        'background: var(--background-3); color: var(--text-primary); border-bottom-left-radius: 4px'"
                                    x-text="msg.body">
                                </div>
                                <span class="text-[10px] px-1 text-(--text-muted)" x-text="msg.created_at"></span>
                            </div>
                        </template>
                    </div>
                    <div class="px-4 py-3 shrink-0 border-t border-(--background-3)">
                        <div class="flex items-end gap-2">
                            <textarea x-model="chatInput" placeholder="{{ __('messages.messages') }}..." rows="1"
                                class="flex-1 resize-none rounded-xl px-3.5 py-2.5 text-sm outline-none transition-colors bg-(--background) text-(--text-primary)"
                                style="max-height: 120px; border: 1px solid var(--background-3)" onfocus="this.style.borderColor='var(--button)'"
                                onblur="this.style.borderColor='var(--background-3)'"
                                @keydown.enter.prevent="if(!$event.shiftKey) sendChatMessage()">
                            </textarea>
                            <button @click="sendChatMessage()"
                                class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors cursor-pointer bg-(--button) hover:bg-(--button-h)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color: var(--button-text)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                        {{ __('messages.sign-out-confirm-title') }}</h2>
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

    <script>
        const header = document.querySelector('header.headroom');
        if (header) {
            const headroom = new Headroom(header, {
                offset: 80,
                tolerance: {
                    up: 5,
                    down: 5
                }
            });
            headroom.init();
        }
    </script>

</div>
