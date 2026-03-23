@extends('layouts.layout')

@section('title', 'rent.use | ' . $user->name)

@section('content')
    <section class="min-h-[calc(100vh-72px)] w-full py-12 bg-(--background)" x-data="{ tab: 'profile' }">
        <div class="max-w-5xl mx-auto px-6 flex flex-col md:flex-row gap-6 items-start">

            <aside class="w-full md:w-56 shrink-0 flex flex-col gap-1 md:sticky md:top-24 z-10">

                <div class="p-4 rounded-sm bg-(--background-2) border border-(--background-3) mb-2 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-sm bg-(--background-3) flex items-center justify-center shrink-0">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover rounded-sm">
                        @else
                            <span
                                class="text-sm font-black text-(--text-primary)">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-(--text-primary) truncate">{{ $user->name }}</p>
                    </div>
                </div>

                @foreach ([['key' => 'profile', 'icon' => 'heroicon-o-user', 'label' => __('messages.profile')], ['key' => 'listings', 'icon' => 'heroicon-o-squares-2x2', 'label' => __('messages.prof-mylisting')], ['key' => 'bookings', 'icon' => 'heroicon-o-calendar', 'label' => __('messages.prof-bookings')], ['key' => 'support', 'icon' => 'heroicon-o-lifebuoy', 'label' => __('messages.prof-support')]] as $item)
                    <button @click="tab = '{{ $item['key'] }}'"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm transition-all text-left cursor-pointer group"
                        :class="tab === '{{ $item['key'] }}'
                            ?
                            'bg-(--background-2) text-(--text-primary) border border-(--background-3)' :
                            'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) border border-transparent'">

                        <x-dynamic-component :component="$item['icon']"
                            class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" />

                        {{ $item['label'] }}
                    </button>
                @endforeach

                <div class="mt-2 border-t border-(--background-3) pt-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-3 w-full px-3 py-2.5 rounded-sm text-sm text-red-400 hover:text-red-300 hover:bg-(--background-2) border border-transparent transition-all cursor-pointer">
                            <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4 shrink-0" />
                            {{ __('messages.sign-out') }}
                        </button>
                    </form>
                </div>

            </aside>

            <div class="flex-1 min-w-0 w-full">

                <div x-show="tab === 'profile'" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

                    <div
                        class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 mb-4 hover:border-(--background-3)/80 transition-colors">

                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.prof-personal-details') }}
                            </h2>

                            <button
                                class="flex items-center gap-1.5 text-xs text-(--text-muted) hover:text-(--background-3) transition-colors cursor-pointer">
                                <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                {{ __('messages.prof-edit') }}
                            </button>
                        </div>

                        <div class="flex items-center gap-4 pb-6 mb-6 border-b border-(--background-3)">
                            <div
                                class="w-12 h-12 rounded-sm bg-(--background-3) flex items-center justify-center shrink-0 cursor-pointer hover:shadow-lg transition-all relative group overflow-hidden">

                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-lg font-black text-(--text-primary)">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                @endif

                                <div
                                    class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                    <x-heroicon-o-camera class="w-4 h-4 text-white" />
                                </div>

                            </div>

                            <div>
                                <p class="text-sm font-black text-(--text-primary)">{{ $user->name }}</p>
                                <p class="text-xs text-(--text-muted)">
                                    {{ __('messages.prof-member-since') }} {{ $user->created_at->format('M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-0">

                            <div class="flex items-center justify-between py-3 border-b border-(--background-3)">
                                <span class="text-xs text-(--text-muted)">
                                    {{ __('messages.prof-name') }}
                                </span>

                                <span class="text-sm text-(--text-primary)">
                                    {{ $user->name }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-3 border-b border-(--background-3)">
                                <span class="text-xs text-(--text-muted)">
                                    {{ __('messages.prof-phone') }}
                                </span>

                                <span class="text-sm text-(--text-primary)">
                                    {{ $user->phone ?? '—' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-3">
                                <span class="text-xs text-(--text-muted)">
                                    {{ __('messages.prof-identity') }}
                                </span>

                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-sm text-xs bg-red-500/10 border border-red-500/20 text-red-400">
                                    <x-heroicon-o-x-circle class="w-3.5 h-3.5" />
                                    {{ __('messages.prof-noverifed') }}
                                </span>
                            </div>

                        </div>
                    </div>

                    <div
                        class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 mb-4 hover:border-(--background-3)/80 transition-colors">

                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.email') }}
                            </h2>

                            <button
                                class="flex items-center gap-1.5 text-xs text-(--text-muted) hover:text-(--background-3) transition-colors cursor-pointer">
                                <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                {{ __('messages.prof-edit') }}
                            </button>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between py-2 gap-3">

                            <div class="flex items-center gap-3">
                                <x-heroicon-o-envelope class="w-4 h-4 text-(--text-muted)" />
                                <span class="text-sm text-(--text-primary)">
                                    {{ $user->email }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">

                                <span
                                    class="text-xs px-2 py-0.5 rounded-sm bg-(--background-3)/20 border border-(--background-3)/30 text-(--background-3)">
                                    {{ __('messages.prof-current') }}
                                </span>

                                @if ($user->email_verified_at)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm bg-green-400/10 border border-green-400/20 text-xs text-green-400">
                                        <x-heroicon-o-check-circle class="w-3.5 h-3.5" />
                                        {{ __('messages.prof-verifed') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-sm bg-red-400/10 border border-red-400/20 text-xs text-red-400">
                                        <x-heroicon-o-x-circle class="w-3.5 h-3.5" />
                                        {{ __('messages.prof-noverifed') }}
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 mb-4 hover:border-(--background-3)/80 transition-colors">

                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.password') }}
                            </h2>

                            <button
                                class="flex items-center gap-1.5 text-xs text-(--text-muted) hover:text-(--background-3) transition-colors cursor-pointer">
                                <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                {{ __('messages.prof-edit') }}
                            </button>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-heroicon-o-lock-closed class="w-4 h-4 text-(--text-muted)" />
                            <span class="text-sm text-(--text-primary) tracking-widest">••••••••••••</span>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                        <div class="p-4 rounded-sm bg-(--background-2) border border-(--background-3) text-center">
                            <p class="text-2xl font-black text-(--text-primary)">0</p>
                            <p class="text-xs text-(--text-muted) mt-1 uppercase tracking-wider">
                                {{ __('messages.prof-listing') }}
                            </p>
                        </div>

                        <div class="p-4 rounded-sm bg-(--background-2) border border-(--background-3) text-center">
                            <p class="text-2xl font-black text-(--text-primary)">0.0</p>
                            <p class="text-xs text-(--text-muted) mt-1 uppercase tracking-wider">
                                {{ __('messages.prof-rating') }}
                            </p>
                        </div>

                        <div class="p-4 rounded-sm bg-(--background-2) border border-(--background-3) text-center">
                            <p class="text-2xl font-black text-(--text-primary)">0</p>
                            <p class="text-xs text-(--text-muted) mt-1 uppercase tracking-wider">
                                {{ __('messages.prof-reviews') }}
                            </p>
                        </div>

                    </div>

                </div>

                <div x-show="tab === 'listings'" x-cloak>

                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">

                        <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-6">
                            {{ __('messages.prof-mylisting') }}
                        </h2>

                        <div class="flex flex-col items-center justify-center py-16 gap-3">

                            <x-heroicon-o-squares-2x2 class="w-8 h-8 text-(--background-3)" />

                            <p class="text-sm text-(--text-muted)">
                                {{ __('messages.prof-nolisting') }}
                            </p>

                            <a href="{{ route('listings.create') }}"
                                class="mt-2 px-4 py-2 text-xs font-semibold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h) transition-all active:scale-95">

                                {{ __('messages.prof-create-listing') }}

                            </a>

                        </div>
                    </div>
                </div>

                <div x-show="tab === 'bookings'" x-cloak>

                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">

                        <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-6">
                            {{ __('messages.prof-bookings') }}
                        </h2>

                        <div class="flex flex-col items-center justify-center py-16 gap-3">

                            <x-heroicon-o-calendar class="w-8 h-8 text-(--background-3)" />

                            <p class="text-sm text-(--text-muted)">
                                {{ __('messages.prof-nobookings') }}
                            </p>

                        </div>
                    </div>
                </div>

                <div x-show="tab === 'support'" x-cloak>

                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">

                        <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-6">
                            {{ __('messages.prof-support') }}
                        </h2>

                        <div class="flex flex-col items-center justify-center py-16 gap-3">

                            <x-heroicon-o-lifebuoy class="w-8 h-8 text-(--background-3)" />

                            <p class="text-sm text-(--text-muted)">
                                {{ __('messages.prof-notickets') }}
                            </p>

                            <a href="mailto:support@rent.use"
                                class="mt-2 px-4 py-2 text-xs font-semibold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h) transition-all active:scale-95">

                                {{ __('messages.prof-create-ticket') }}

                            </a>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

@endsection
