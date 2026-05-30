<?php

use Livewire\Component;

new class extends Component {}; ?>

<div x-data="chatComponent()" x-cloak>

    {{-- DESKTOP HEADER --}}
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
                <a href="/"
                    class="rounded-xl border-b border-transparent p-1 transition-all duration-200 hover:border-blue-500 focus:border-blue-500 focus:outline-none">
                    <img src="{{ asset('storage/images/logo.svg') }}" alt="rent.use" class="w-14 h-8">
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
                    <button @click="bookingsModal = true"
                        class="p-1.5 rounded-sm text-(--text-btn-header) hover:text-(--button-h) cursor-pointer">
                        <x-heroicon-o-calendar class="w-5 h-5" />
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
                            <div class="p-3 border-b border-(--background-3)">
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
                <button @click="bookingsModal = true"
                    class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                    <x-heroicon-o-calendar class="w-5 h-5" />
                    <span class="text-[10px] font-medium">{{ __('messages.rent') }}</span>
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
                <div class="relative">
                    <button @click="userMenu = !userMenu"
                        class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl cursor-pointer">
                        <div
                            class="w-7 h-7 rounded-full overflow-hidden bg-(--background-3) flex items-center justify-center">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-semibold text-(--text-muted) uppercase">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] font-medium text-(--text-muted)">Menu</span>
                    </button>

                    <div x-show="userMenu" x-cloak @click.away="userMenu = false"
                        class="absolute bottom-full right-0 mb-2 w-52 rounded-xl border border-(--background-3) bg-(--background-2) shadow-2xl z-50 overflow-hidden">
                        <div class="p-3 border-b border-(--background-3)">
                            <p class="text-xs font-semibold text-(--text-primary)">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-(--text-muted) truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="p-1.5 flex flex-col gap-0.5">
                            <a href="/profile"
                                class="flex items-center gap-2.5 px-3 py-2 text-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-lg">
                                <x-heroicon-o-user class="w-4 h-4 shrink-0" />
                                Profile
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
                    <span class="text-[10px] font-medium">Login</span>
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
                            ->where('status', 'active')
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
                                    class="flex items-center gap-3 p-2 rounded-sm hover:bg-(--background-3) transition-colors group">
                                    <a href="{{ route('listings.show', $fav->slug) }}" @click="favoritesModal = false"
                                        class="shrink-0 w-16 h-14 rounded-sm overflow-hidden bg-(--background-3)">
                                        @if ($fav->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $fav->images->first()->path) }}"
                                                alt="{{ $fav->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <x-heroicon-o-photo class="w-5 h-5 text-(--text-muted) opacity-40" />
                                            </div>
                                        @endif
                                    </a>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('listings.show', $fav->slug) }}"
                                            @click="favoritesModal = false">
                                            <p
                                                class="text-sm font-semibold text-(--text-primary) truncate hover:underline">
                                                {{ $fav->title }}</p>
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="flex items-center gap-1 text-[11px] text-(--text-muted)">
                                                <x-heroicon-s-map-pin class="w-2.5 h-2.5 shrink-0" />
                                                {{ $fav->city->name }}
                                            </span>
                                            <span class="text-[11px] font-bold text-(--button)">
                                                @if ($fav->price_per_day)
                                                    {{ number_format($fav->price_per_day, 0, '.', ' ') }}
                                                    {{ $fav->currency }}<span
                                                        class="text-(--text-muted) font-normal">/day</span>
                                                @elseif($fav->price_per_hour)
                                                    {{ number_format($fav->price_per_hour, 0, '.', ' ') }}
                                                    {{ $fav->currency }}<span
                                                        class="text-(--text-muted) font-normal">/hr</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('favorites.destroy', $fav) }}"
                                        class="shrink-0">
                                        @csrf @method('DELETE')
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
                {{--
                @if ($userFavorites->isNotEmpty())
                    <div class="px-5 py-3 border-t border-(--background-3) shrink-0">
                        <a href="{{ route('favorites.index') }}" @click="favoritesModal = false"
                            class="flex items-center justify-center gap-1.5 w-full py-2 text-xs font-medium text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) rounded-sm transition-colors">
                            {{ __('messages.favorite') }} ({{ $userFavorites->count() }})
                            <x-heroicon-o-arrow-right class="w-3 h-3" />
                        </a>
                    </div>
                @endif --}}
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
                class="relative z-10 w-screen max-w-md flex flex-col bg-(--background-2) border-l border-(--background-3) shadow-2xl h-full">

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
                        class="flex-1 px-4 py-3 text-xs font-semibold transition-colors">
                        {{ __('messages.incoming_requests') }}
                        @php $pendingCount = auth()->user()->bookingsAsOwner()->where('status', 'pending')->count(); @endphp
                        @if ($pendingCount > 0)
                            <span
                                class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold bg-(--button) text-(--button-text) rounded-full">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 p-4">
                    @php
                        $myRentals = auth()
                            ->user()
                            ->bookingsAsRenter()
                            ->with(['listing.images', 'listing.city'])
                            ->latest()
                            ->get();
                        $incomingRequests = auth()
                            ->user()
                            ->bookingsAsOwner()
                            ->with(['listing', 'renter'])
                            ->latest()
                            ->get();
                        $statusConfig = [
                            'pending' => ['bg-yellow-400/10 text-yellow-500', __('messages.status_pending')],
                            'confirmed' => ['bg-green-400/10 text-green-500', __('messages.status_confirmed')],
                            'cancelled' => ['bg-red-400/10 text-red-400', __('messages.status_cancelled')],
                            'completed' => ['bg-(--background-3) text-(--text-muted)', __('messages.status_completed')],
                        ];
                    @endphp

                    <div x-show="bookingsTab === 'renter'">
                        @if ($myRentals->isEmpty())
                            <div class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                                <x-heroicon-o-calendar class="w-10 h-10 text-(--text-muted) opacity-30" />
                                <p class="text-sm font-medium text-(--text-primary)">{{ __('messages.no_rentals_title') }}
                                </p>
                                <p class="text-xs text-(--text-muted) max-w-xs">{{ __('messages.no_rentals_desc') }}</p>
                                <a href="{{ route('search') }}" @click="bookingsModal = false"
                                    class="mt-2 px-4 py-2 text-xs font-medium bg-(--button) text-(--button-text) hover:bg-(--button-h) rounded-sm">
                                    {{ __('messages.browse') }}
                                </a>
                            </div>
                        @else
                            <div class="flex flex-col gap-2">
                                @foreach ($myRentals as $booking)
                                    @php [$statusClass, $statusLabel] = $statusConfig[$booking->status] ?? ['', $booking->status]; @endphp
                                    <div
                                        class="p-3 rounded-sm border border-(--background-3) bg-(--background) flex flex-col gap-2">
                                        <a href="{{ route('listings.show', $booking->listing->slug) }}"
                                            @click="bookingsModal = false" class="flex items-center gap-3 group">
                                            <div class="shrink-0 w-14 h-12 rounded-sm overflow-hidden bg-(--background-3)">
                                                @if ($booking->listing->images->isNotEmpty())
                                                    <img src="{{ asset('storage/' . $booking->listing->images->first()->path) }}"
                                                        class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-semibold text-(--text-primary) truncate group-hover:underline">
                                                    {{ $booking->listing->title }}</p>
                                                <p class="text-[11px] text-(--text-muted)">
                                                    {{ $booking->listing->city->name }}</p>
                                            </div>
                                        </a>
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-(--text-muted)">
                                                @if ($booking->pricing_mode === 'hour')
                                                    {{ $booking->start_date->format('d M Y') }} ·
                                                    {{ $booking->start_hour }} – {{ $booking->end_hour }}
                                                @else
                                                    {{ $booking->start_date->format('d M') }} —
                                                    {{ $booking->end_date->format('d M Y') }}
                                                @endif
                                            </span>
                                            <span
                                                class="font-semibold text-(--text-primary)">{{ number_format($booking->total_price) }}
                                                {{ $booking->currency }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[11px] font-medium {{ $statusClass }}">{{ $statusLabel }}</span>
                                            @if ($booking->isPending())
                                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="cancelled_by" value="renter">
                                                    <button type="submit"
                                                        class="text-[11px] text-red-400 hover:text-red-300 cursor-pointer">{{ __('messages.cancel') }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div x-show="bookingsTab === 'owner'">
                        @if ($incomingRequests->isEmpty())
                            <div class="flex flex-col items-center justify-center gap-3 py-16 text-center">
                                <x-heroicon-o-inbox class="w-10 h-10 text-(--text-muted) opacity-30" />
                                <p class="text-sm font-medium text-(--text-primary)">
                                    {{ __('messages.no_requests_title') }}</p>
                                <p class="text-xs text-(--text-muted) max-w-xs">{{ __('messages.no_requests_desc') }}</p>
                            </div>
                        @else
                            <div class="flex flex-col gap-2">
                                @foreach ($incomingRequests as $booking)
                                    @php [$statusClass, $statusLabel] = $statusConfig[$booking->status] ?? ['', $booking->status]; @endphp
                                    <div
                                        class="p-3 rounded-sm border border-(--background-3) bg-(--background) flex flex-col gap-2">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="shrink-0 w-9 h-9 rounded-sm bg-(--background-3) overflow-hidden flex items-center justify-center">
                                                @if ($booking->renter->avatar)
                                                    <img src="{{ asset('storage/' . $booking->renter->avatar) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <span
                                                        class="text-xs font-medium text-(--text-muted) uppercase">{{ mb_substr($booking->renter->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-(--text-primary) truncate">
                                                    {{ $booking->renter->name }}</p>
                                                <p class="text-[11px] text-(--text-muted) truncate">
                                                    {{ $booking->listing->title }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-(--text-muted)">{{ $booking->start_date->format('d M') }} —
                                                {{ $booking->end_date->format('d M Y') }}</span>
                                            <span
                                                class="font-semibold text-(--text-primary)">{{ number_format($booking->total_price) }}
                                                {{ $booking->currency }}</span>
                                        </div>
                                        @if ($booking->isPending())
                                            <div class="flex gap-2 mt-1">
                                                <form method="POST" action="{{ route('bookings.confirm', $booking) }}"
                                                    class="flex-1">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="w-full py-1.5 text-xs font-medium bg-green-500/10 text-green-500 hover:bg-green-500/20 rounded-sm cursor-pointer transition-colors">{{ __('messages.confirm') }}</button>
                                                </form>
                                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                                                    class="flex-1">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="cancelled_by" value="owner">
                                                    <button type="submit"
                                                        class="w-full py-1.5 text-xs font-medium bg-red-400/10 text-red-400 hover:bg-red-400/20 rounded-sm cursor-pointer transition-colors">{{ __('messages.decline') }}</button>
                                                </form>
                                            </div>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[11px] font-medium w-fit {{ $statusClass }}">{{ $statusLabel }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
                        <p class="text-sm font-medium text-(--text-primary)">No messages yet</p>
                        <p class="text-xs text-(--text-muted)">Write to a listing owner to start a conversation</p>
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
                                            <span x-show="chat.last_message?.is_mine">You: </span>
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
                                <div class="max-w-[75%] px-3 py-2 rounded-2xl text-sm leading-relaxed break-words"
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
