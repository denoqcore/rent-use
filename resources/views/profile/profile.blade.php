@extends('layouts.layout')

@section('title', 'rent.use | ' . $user->name)

@section('content')
    <section class="min-h-[calc(100vh-72px)] w-full pt-20 lg:pt-40 py-12 bg-(--background)" x-data="{
        tab: {
            '#listings': 'listings',
            '#bookings': 'bookings',
            '#subscription': 'subscription',
            '#support': 'support',
        } [window.location.hash] ?? 'profile',
        editInfo: false,
        editPassword: false
    }">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row gap-6 items-start">
            <aside class="w-full md:w-56 shrink-0 flex flex-col gap-1 md:sticky md:top-24 z-10">
                <div class="p-4 rounded-sm bg-(--background-2) border border-(--background-3) mb-2 flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-sm bg-(--background-3) flex items-center justify-center shrink-0 overflow-hidden">
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

                @foreach ([['key' => 'profile', 'icon' => 'heroicon-o-user', 'label' => __('messages.profile')], ['key' => 'listings', 'icon' => 'heroicon-o-squares-2x2', 'label' => __('messages.prof-mylisting')], ['key' => 'bookings', 'icon' => 'heroicon-o-calendar', 'label' => __('messages.prof-bookings')], ['key' => 'subscription', 'icon' => 'heroicon-o-star', 'label' => 'Subscription'], ['key' => 'support', 'icon' => 'heroicon-o-lifebuoy', 'label' => __('messages.prof-support')]] as $item)
                    <button @click="tab = '{{ $item['key'] }}'"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-sm text-sm transition-all text-left cursor-pointer group"
                        :class="tab === '{{ $item['key'] }}'
                            ?
                            'bg-(--background-2) text-(--text-primary) border border-(--background-3)' :
                            'text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-2) border border-transparent'">
                        <x-dynamic-component :component="$item['icon']" class="w-4 h-4 shrink-0" />
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
                @if (session('success_info') || session('success_password') || session('success'))
                    <div
                        class="mb-4 px-4 py-3 rounded-sm text-sm text-green-500 bg-green-500/10 border border-green-500/20">
                        {{ session('success_info') ?? (session('success_password') ?? session('success')) }}
                    </div>
                @endif
                <div x-show="tab === 'profile'" x-cloak x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 mb-4">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.prof-personal-details') }}
                            </h2>
                            <button @click="editInfo = !editInfo"
                                class="flex items-center gap-1.5 text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                                <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                <span x-text="editInfo ? 'Cancel' : '{{ __('messages.prof-edit') }}'"></span>
                            </button>
                        </div>

                        <div class="flex items-center gap-4 pb-6 mb-6 border-b border-(--background-3)">
                            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data"
                                x-data="{ preview: null }" class="relative shrink-0">
                                @csrf
                                <label
                                    class="w-16 h-16 rounded-sm bg-(--background-3) flex items-center justify-center cursor-pointer hover:shadow-lg transition-all relative group overflow-hidden">
                                    <template x-if="preview">
                                        <img :src="preview" class="w-full h-full object-cover absolute inset-0">
                                    </template>
                                    <template x-if="!preview">
                                        @if ($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                                class="w-full h-full object-cover absolute inset-0">
                                        @else
                                            <span
                                                class="text-lg font-black text-(--text-primary)">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        @endif
                                    </template>
                                    <div
                                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                        <x-heroicon-o-camera class="w-4 h-4 text-white" />
                                    </div>
                                    <input type="file" name="avatar" accept="image/*" class="hidden"
                                        @change="
                preview = URL.createObjectURL($event.target.files[0]);
                $nextTick(() => $el.closest('form').submit());
            ">
                                </label>
                            </form>

                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-3">
                                    <p class="text-sm font-black text-(--text-primary)">{{ $user->name }}</p>

                                    @if (Auth::user()->plan === 'premium')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold bg-yellow-400/10 text-yellow-400 border border-yellow-400/20">
                                            PREMIUM
                                        </span>
                                    @elseif (Auth::user()->plan === 'pro')
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold bg-blue-400/10 text-blue-400 border border-blue-400/20">
                                            PRO
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-sm text-[10px] font-bold bg-(--background-3) text-(--text-muted) border border-(--background-3)">
                                            STARTER
                                        </span>
                                    @endif
                                </div>

                                <p class="text-xs text-(--text-muted)">
                                    {{ __('messages.prof-member-since') }} {{ $user->created_at->format('M Y') }}
                                </p>
                            </div>
                        </div>

                        <div x-show="!editInfo" class="space-y-0">
                            <div class="flex items-center justify-between py-3 border-b border-(--background-3)">
                                <span class="text-xs text-(--text-muted)">{{ __('messages.prof-name') }}</span>
                                <span class="text-sm text-(--text-primary)">{{ $user->name }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-(--background-3)">
                                <span class="text-xs text-(--text-muted)">{{ __('messages.prof-phone') }}</span>
                                <span
                                    class="text-sm text-(--text-primary)">{{ $user->phone ? formatPhone($user->phone) : '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-xs text-(--text-muted)">{{ __('messages.prof-identity') }}</span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-sm text-xs bg-red-500/10 border border-red-500/20 text-red-400">
                                    <x-heroicon-o-x-circle class="w-3.5 h-3.5" />
                                    {{ __('messages.prof-noverifed') }}
                                </span>
                            </div>
                        </div>
                        <div x-show="editInfo" x-transition>
                            <form method="POST" action="{{ route('profile.info') }}" class="flex flex-col gap-4">
                                @csrf
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-(--text-muted)">{{ __('messages.prof-name') }}</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                                    @error('name')
                                        <p class="text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-(--text-muted)">{{ __('messages.prof-phone') }}</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                                    @error('phone')
                                        <p class="text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-semibold rounded-sm cursor-pointer transition-colors"
                                        style="background: var(--button); color: var(--button-text)">
                                        Save
                                    </button>
                                    <button type="button" @click="editInfo = false"
                                        class="px-4 py-2 text-sm rounded-sm cursor-pointer text-(--text-muted) hover:text-(--text-primary) border border-(--background-3)">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.email') }}</h2>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <x-heroicon-o-envelope class="w-4 h-4 text-(--text-muted)" />
                                <span class="text-sm text-(--text-primary)">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-xs px-2 py-0.5 rounded-sm bg-(--background-3)/20 border border-(--background-3)/30 text-(--text-muted)">
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
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.password') }}</h2>
                            <button @click="editPassword = !editPassword"
                                class="flex items-center gap-1.5 text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                                <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                <span x-text="editPassword ? 'Cancel' : '{{ __('messages.prof-edit') }}'"></span>
                            </button>
                        </div>

                        <div x-show="!editPassword">
                            <div class="flex items-center gap-3">
                                <x-heroicon-o-lock-closed class="w-4 h-4 text-(--text-muted)" />
                                <span class="text-sm text-(--text-primary) tracking-widest">••••••••••••</span>
                            </div>
                        </div>

                        <div x-show="editPassword" x-transition>
                            <form method="POST" action="{{ route('profile.password') }}" class="flex flex-col gap-4">
                                @csrf
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-(--text-muted)">Current password</label>
                                    <input type="password" name="current_password"
                                        class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted)">
                                    @error('current_password')
                                        <p class="text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-(--text-muted)">New password</label>
                                    <input type="password" name="password"
                                        class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted)">
                                    @error('password')
                                        <p class="text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-(--text-muted)">Confirm new password</label>
                                    <input type="password" name="password_confirmation"
                                        class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted)">
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-semibold rounded-sm cursor-pointer"
                                        style="background: var(--button); color: var(--button-text)">
                                        Update password
                                    </button>
                                    <button type="button" @click="editPassword = false"
                                        class="px-4 py-2 text-sm rounded-sm cursor-pointer text-(--text-muted) border border-(--background-3)">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-sm bg-(--background-2) border border-(--background-3) text-center">
                            <p class="text-2xl font-black text-(--text-primary)">{{ $listings->count() }}</p>
                            <p class="text-xs text-(--text-muted) mt-1 uppercase tracking-wider">
                                {{ __('messages.prof-listing') }}</p>
                        </div>
                        <div class="p-4 rounded-sm bg-(--background-2) border border-(--background-3) text-center">
                            <p class="text-2xl font-black text-(--text-primary)">{{ number_format($avgRating, 1) }}</p>
                            <p class="text-xs text-(--text-muted) mt-1 uppercase tracking-wider">
                                {{ __('messages.prof-rating') }}</p>
                        </div>
                        <div class="p-4 rounded-sm bg-(--background-2) border border-(--background-3) text-center">
                            <p class="text-2xl font-black text-(--text-primary)">{{ $reviewCount }}</p>
                            <p class="text-xs text-(--text-muted) mt-1 uppercase tracking-wider">
                                {{ __('messages.prof-reviews') }}</p>
                        </div>
                    </div>
                </div>
                <div x-show="tab === 'listings'" x-cloak>
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.prof-mylisting') }}
                            </h2>

                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-(--text-primary) tabular-nums">
                                    {{ $listingsCount }} <span class="text-(--text-muted)">/ {{ $listingsLimit }}</span>
                                </span>
                                @if ($listingsCount >= $listingsLimit)
                                    <a href="{{ route('subscription.index') }}"
                                        class="inline-flex items-center text-xs font-medium px-3 py-1.5 rounded-md border border-(--background-3) bg-(--background) text-(--text-primary) hover:bg-(--background-3) transition-colors">
                                        {{ __('messages.prof-upgrade') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if ($listings->isEmpty())
                            <div class="flex flex-col items-center justify-center py-16 gap-3">
                                <x-heroicon-o-squares-2x2 class="w-8 h-8 text-(--background-3)" />
                                <p class="text-sm text-(--text-muted)">{{ __('messages.prof-nolisting') }}</p>
                                <a href="{{ route('listings.create') }}"
                                    class="mt-2 px-4 py-2 text-xs font-semibold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h) transition-all">
                                    {{ __('messages.prof-create-listing') }}
                                </a>
                            </div>
                        @else
                            <div x-data="{ confirmDelete: false, deleteUrl: '', deleteTitle: '' }" class="flex flex-col gap-4">
                                @foreach ($listings as $listing)
                                    <div
                                        class="flex flex-col sm:grid sm:grid-cols-[200px_1fr] md:grid-cols-[240px_1fr] gap-4 p-4 rounded-sm border border-(--background-3) hover:border-(--text-muted)/30 transition-colors sm:h-44">

                                        <a href="{{ route('listings.show', $listing->slug) }}"
                                            class="relative block w-full h-40 sm:h-full rounded-sm overflow-hidden bg-(--background-3) shrink-0">
                                            @if ($listing->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <x-heroicon-o-photo class="w-6 h-6 text-(--text-muted) opacity-40" />
                                                </div>
                                            @endif
                                        </a>

                                        <div class="flex flex-col justify-between min-w-0 flex-1 py-0.5 gap-4 sm:gap-0">
                                            <div class="flex justify-between items-start gap-3">
                                                <div class="min-w-0">
                                                    <a href="{{ route('listings.show', $listing->slug) }}">
                                                        <h3
                                                            class="text-sm sm:text-base font-semibold text-(--text-primary) truncate hover:underline">
                                                            {{ $listing->title }}
                                                        </h3>
                                                    </a>
                                                    <p class="text-xs text-(--text-muted) mt-1">{{ $listing->city->name }}
                                                    </p>
                                                </div>
                                                <div class="flex flex-col items-end gap-1.5 shrink-0">
                                                    <span
                                                        class="text-sm sm:text-base font-bold text-(--blackwhite) whitespace-nowrap">
                                                        @if ($listing->price_per_day)
                                                            {{ number_format($listing->price_per_day) }}
                                                            {{ $listing->currency }}/day
                                                        @elseif ($listing->price_per_hour)
                                                            {{ number_format($listing->price_per_hour) }}
                                                            {{ $listing->currency }}/hr
                                                        @endif
                                                    </span>
                                                    <div class="mt-5">
                                                        @if ($listing->status === 'active')
                                                            @if ($listing->is_boosted && $listing->boosted_until?->isFuture())
                                                                <span
                                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-sm font-medium text-xs text-(--text-primary)">
                                                                    <x-heroicon-o-chevron-double-up class="w-3 h-3" />
                                                                    Available in
                                                                    {{ now()->diffForHumans($listing->boosted_until, true) }}
                                                                </span>
                                                            @else
                                                                <form method="POST"
                                                                    action="{{ route('listings.boost', $listing) }}">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-sm font-medium text-xs cursor-pointer border border-(--blackwhite) text-(--text-primary) transition-colors">
                                                                        <x-heroicon-o-chevron-double-up class="w-3 h-3" />
                                                                        Boost
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-end sm:gap-4 mt-auto">
                                                <div class="flex items-center gap-3 text-xs text-(--text-muted)">
                                                    <span>{{ $listing->created_at->format('d.m.Y') }}</span>
                                                    <span class="w-1 h-1 rounded-full bg-(--background-3)"></span>
                                                    <span>{{ $listing->category->name ?? 'Category' }}</span>
                                                </div>

                                                <div
                                                    class="flex items-center gap-1.5 overflow-x-auto sm:overflow-visible pb-1 sm:pb-0 shrink-0">
                                                    @php
                                                        $statusClass = match ($listing->status) {
                                                            'active' => 'bg-green-500/10 text-green-500',
                                                            'paused' => 'bg-yellow-500/10 text-yellow-500',
                                                            'archived' => 'bg-(--background-3) text-(--text-muted)',
                                                            default => 'bg-(--background-3) text-(--text-muted)',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="text-[10px] sm:text-[11px] px-2.5 py-1 rounded-md font-medium mr-1 {{ $statusClass }}">
                                                        {{ $listing->status }}
                                                    </span>

                                                    <a href="{{ route('listings.edit', $listing->slug) }}"
                                                        class="p-2 rounded-sm text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) transition-colors">
                                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                                    </a>

                                                    <form method="POST"
                                                        action="{{ route('listings.pause', $listing) }}">
                                                        @csrf
                                                        <button type="submit"
                                                            title="{{ $listing->status === 'paused' ? 'Resume listing' : 'Pause listing' }}"
                                                            class="p-2 rounded-sm transition-colors cursor-pointer {{ $listing->status === 'paused' ? 'text-yellow-500 bg-yellow-500/10 hover:bg-yellow-500/20' : 'text-(--text-muted) hover:text-yellow-500 hover:bg-yellow-500/10' }}">
                                                            @if ($listing->status === 'paused')
                                                                <x-heroicon-o-play class="w-4 h-4" />
                                                            @else
                                                                <x-heroicon-o-pause class="w-4 h-4" />
                                                            @endif
                                                        </button>
                                                    </form>

                                                    @if ($listing->status === 'active')
                                                        <form method="POST"
                                                            action="{{ route('listings.archive', $listing) }}">
                                                            @csrf
                                                            <button type="submit" title="Archive listing"
                                                                class="p-2 rounded-sm text-(--text-muted) hover:text-green-500 hover:bg-green-500/10 transition-colors cursor-pointer">
                                                                <x-heroicon-o-archive-box class="w-4 h-4" />
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if ($listing->status === 'archived')
                                                        <form method="POST"
                                                            action="{{ route('listings.restore', $listing) }}">
                                                            @csrf
                                                            <button type="submit" title="Restore listing"
                                                                class="p-2 rounded-sm text-(--text-muted) hover:text-green-500 hover:bg-green-500/10 transition-colors cursor-pointer">
                                                                <x-heroicon-o-arrow-path class="w-4 h-4" />
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <button type="button"
                                                        @click="deleteUrl = '{{ route('listings.destroy', $listing->slug) }}'; deleteTitle = '{{ e($listing->title) }}'; confirmDelete = true"
                                                        title="Delete listing"
                                                        class="p-2 rounded-sm text-(--text-muted) hover:text-red-400 hover:bg-red-400/10 transition-colors cursor-pointer">
                                                        <x-heroicon-o-trash class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach

                                <div x-show="confirmDelete" x-cloak
                                    class="fixed inset-0 z-50 flex items-end sm:items-center sm:justify-center p-0 sm:p-4 bg-black/40 backdrop-blur-xs"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-150">

                                    <div @click.away="confirmDelete = false"
                                        class="w-full sm:max-w-md p-6 bg-(--background-2) border-t sm:border border-(--background-3) rounded-t-lg sm:rounded-sm shadow-2xl"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="translate-y-0 sm:scale-100">

                                        <div class="w-12 h-1 bg-(--background-3) rounded-full mx-auto mb-5 sm:hidden">
                                        </div>

                                        <h3 class="text-base font-bold text-(--text-primary) mb-2">Delete this ad?</h3>

                                        <p class="text-sm text-(--text-muted) mb-6 leading-relaxed">
                                            Are you sure you want to completely delete <span
                                                class="text-(--text-primary) font-semibold"
                                                x-text="'«' + deleteTitle + '»'"></span>.
                                            It will be impossible to restore it.
                                        </p>

                                        <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                                            <button type="button" @click="confirmDelete = false"
                                                class="w-full sm:w-auto order-2 sm:order-1 px-5 py-3 sm:py-2 text-sm font-medium text-center rounded-sm border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background) cursor-pointer transition-colors">
                                                Cancel
                                            </button>

                                            <form method="POST" :action="deleteUrl"
                                                class="w-full sm:w-auto order-1 sm:order-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full sm:w-auto px-5 py-3 sm:py-2 text-sm font-medium text-center rounded-sm bg-red-500 hover:bg-red-600 text-white cursor-pointer transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <a href="{{ route('listings.create') }}"
                                class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 text-xs font-semibold rounded-sm border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background-3) transition-colors">
                                <x-heroicon-o-plus class="w-4 h-4" />
                                {{ __('messages.prof-create-listing') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div x-show="tab === 'bookings'" x-cloak>
                    <div class="rounded-lg border border-(--background-3) bg-(--background-2) p-4 sm:p-6">
                        <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-6">
                            {{ __('messages.prof-bookings') }}
                        </h2>

                        @if ($bookings->isEmpty())
                            <div class="flex flex-col items-center justify-center py-16 gap-3">
                                <x-heroicon-o-calendar class="w-8 h-8 text-(--background-3)" />
                                <p class="text-sm text-(--text-muted)">{{ __('messages.prof-nobookings') }}</p>
                            </div>
                        @else
                            <div x-data="{ confirmDelete: false, deleteUrl: '' }" class="flex flex-col gap-4">
                                @foreach ($bookings as $booking)
                                    <div
                                        class="flex flex-col sm:grid sm:grid-cols-[240px_1fr] gap-4 p-4 rounded-lg border border-(--background-3) hover:border-(--text-muted)/30 transition-colors sm:h-44">

                                        <a href="{{ route('listings.show', $booking->listing->slug) }}"
                                            class="relative block w-full h-40 sm:h-full rounded-md overflow-hidden bg-(--background-3) shrink-0">
                                            @if ($booking->listing->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $booking->listing->images->first()->path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <x-heroicon-o-photo class="w-6 h-6 text-(--text-muted) opacity-40" />
                                                </div>
                                            @endif
                                        </a>

                                        <div class="flex flex-col justify-between min-w-0 flex-1 py-0.5 gap-4 sm:gap-0">
                                            <div class="flex justify-between items-start gap-3">
                                                <div class="min-w-0">
                                                    <a href="{{ route('listings.show', $booking->listing->slug) }}">
                                                        <h3
                                                            class="text-sm sm:text-base font-semibold text-(--text-primary) truncate hover:underline">
                                                            {{ $booking->listing->title }}
                                                        </h3>
                                                    </a>
                                                    <p class="text-xs text-(--text-muted) mt-1">
                                                        {{ $booking->listing->city->name }}</p>
                                                </div>
                                                <span
                                                    class="text-sm sm:text-base font-bold text-(--blackwhite) whitespace-nowrap shrink-0">
                                                    {{ number_format($booking->total_price, 0, ',', ' ') }}
                                                    {{ $booking->currency }}
                                                </span>
                                            </div>

                                            <div
                                                class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-end mt-auto">
                                                <div class="flex items-center gap-2 text-xs text-(--text-muted)">
                                                    @if ($booking->pricing_mode === 'hour')
                                                        <span>{{ $booking->start_date->format('d M Y') }} ·
                                                            {{ $booking->start_hour }} – {{ $booking->end_hour }}</span>
                                                    @else
                                                        <span>{{ $booking->start_date->format('d M') }} —
                                                            {{ $booking->end_date->format('d M Y') }}</span>
                                                        <span class="w-1 h-1 rounded-full bg-(--background-3)"></span>
                                                    @endif
                                                </div>

                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    @php
                                                        $statusClass = match ($booking->status) {
                                                            'pending' => 'bg-yellow-400/10 text-yellow-500',
                                                            'confirmed' => 'bg-green-400/10 text-green-500',
                                                            'cancelled' => 'bg-red-400/10 text-red-400',
                                                            'completed' => 'bg-(--background-3) text-(--text-muted)',
                                                            default => 'bg-(--background-3) text-(--text-muted)',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="text-[11px] font-medium px-2.5 py-1 rounded-full mr-1 {{ $statusClass }}">
                                                        {{ $booking->status }}
                                                    </span>
                                                    <button type="button" {{-- @click="deleteUrl = '{{ route('bookings.destroy', $booking) }}'; confirmDelete = true" --}} title="Delete booking"
                                                        class="p-2 rounded-md text-(--text-muted) hover:text-red-400 hover:bg-red-400/10 transition-colors cursor-pointer">
                                                        <x-heroicon-o-trash class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- <div x-show="confirmDelete" x-cloak
                                    class="fixed inset-0 z-50 flex items-end sm:items-center sm:justify-center p-0 sm:p-4 bg-black/40 backdrop-blur-xs"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-150">
                                    <div @click.away="confirmDelete = false"
                                        class="w-full sm:max-w-md p-6 bg-(--background-2) border-t sm:border border-(--background-3) rounded-t-lg sm:rounded-lg shadow-2xl"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="translate-y-0 sm:scale-100">
                                        <div class="w-12 h-1 bg-(--background-3) rounded-full mx-auto mb-5 sm:hidden">
                                        </div>
                                        <h3 class="text-base font-bold text-(--text-primary) mb-2">Delete booking?</h3>
                                        <p class="text-sm text-(--text-muted) mb-6 leading-relaxed">
                                            This will remove the booking from your history.
                                        </p>
                                        <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                                            <button type="button" @click="confirmDelete = false"
                                                class="w-full sm:w-auto order-2 sm:order-1 px-5 py-3 sm:py-2 text-sm font-medium text-center rounded-md border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) hover:bg-(--background) cursor-pointer transition-colors">
                                                Cancel
                                            </button>
                                            <form method="POST" :action="deleteUrl"
                                                class="w-full sm:w-auto order-1 sm:order-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full sm:w-auto px-5 py-3 sm:py-2 text-sm font-medium text-center rounded-md bg-red-500 hover:bg-red-600 text-white cursor-pointer transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        @endif
                    </div>
                </div>

                <div x-show="tab === 'subscription'" x-cloak>
                    @if (session('error'))
                        <div class="mb-4 px-4 py-3 rounded-sm text-sm text-red-400 bg-red-400/10 border border-red-400/20">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 mb-4">
                        <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-4">Current plan
                        </h2>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if ($user->plan === 'premium')
                                    <span
                                        class="px-2.5 py-1 rounded-sm text-xs font-bold bg-yellow-400/10 text-yellow-400 border border-yellow-400/20">PREMIUM</span>
                                @elseif($user->plan === 'pro')
                                    <span
                                        class="px-2.5 py-1 rounded-sm text-xs font-bold bg-blue-400/10 text-blue-400 border border-blue-400/20">PRO</span>
                                @else
                                    <span
                                        class="px-2.5 py-1 rounded-sm text-xs font-bold bg-(--background-3) text-(--text-muted) border border-(--background-3)">STARTER</span>
                                @endif

                                @if ($user->plan_expires_at && $user->plan_expires_at->isFuture() && now()->diffInDays($user->plan_expires_at) <= 5)
                                    <span class="text-xs text-(--text-muted)">
                                        Until {{ $user->plan_expires_at->format('d.m.Y') }}
                                        <span class="text-red-400 ml-1">Is expiring</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-3">
                            <div class="p-3 rounded-sm bg-(--background) border border-(--background-3) text-center">
                                <p class="text-lg font-black text-(--text-primary)">{{ $user->maxListings() }}</p>
                                <p class="text-[11px] text-(--text-muted) mt-0.5">listings</p>
                            </div>
                            <div class="p-3 rounded-sm bg-(--background) border border-(--background-3) text-center">
                                <p class="text-lg font-black text-(--text-primary)">{{ $user->maxPhotos() }}</p>
                                <p class="text-[11px] text-(--text-muted) mt-0.5">photo</p>
                            </div>
                            <div class="p-3 rounded-sm bg-(--background) border border-(--background-3) text-center">
                                <p class="text-lg font-black text-(--text-primary)">{{ $user->maxBoostedListings() }}</p>
                                <p class="text-[11px] text-(--text-muted) mt-0.5">boost</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        @foreach ([
            'pro' => [
                'label' => 'PRO',
                'price' => '199 MDL',
                'color' => 'blue',
                'features' => ['Up to 12 listings', 'Up to 8 photos', '1 boost to the top day', 'Search priority'],
            ],
            'premium' => [
                'label' => 'PREMIUM',
                'price' => '349 MDL',
                'color' => 'yellow',
                'features' => ['Up to 20 listings', 'Up to 8 photos', '3 boost to the top day', 'Search priority'],
            ],
        ] as $key => $plan)
                            @php $isCurrent = $user->plan === $key && $user->isActivePlan(); @endphp
                            <div
                                class="rounded-sm border p-5 flex flex-col gap-4
                             {{ $isCurrent
                                 ? ($key === 'premium'
                                     ? 'border-yellow-400/30 bg-yellow-400/5'
                                     : 'border-blue-400/30 bg-blue-400/5')
                                 : 'border-(--background-3) bg-(--background-2)' }}">

                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm font-bold
                                        {{ $key === 'premium' ? 'text-yellow-400' : 'text-blue-400' }}">
                                        {{ $plan['label'] }}
                                    </span>
                                    @if ($isCurrent)
                                        <span
                                            class="text-[11px] px-2 py-0.5 rounded-sm
                                               {{ $key === 'premium' ? 'bg-yellow-400/10 text-yellow-400 border border-yellow-400/20' : 'bg-blue-400/10 text-blue-400 border border-blue-400/20' }}">
                                            Active
                                        </span>
                                    @endif
                                </div>

                                <p class="text-2xl font-black text-(--text-primary)">{{ $plan['price'] }}<span
                                        class="text-sm font-normal text-(--text-muted)">/month</span></p>

                                <ul class="flex flex-col gap-2">
                                    @foreach ($plan['features'] as $feature)
                                        <li class="flex items-center gap-2 text-xs text-(--text-muted)">
                                            <x-heroicon-o-check class="w-3.5 h-3.5 text-green-400 shrink-0" />
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>

                                @if ($isCurrent)
                                    <span
                                        class="mt-auto text-center py-2 text-xs text-(--text-muted) border border-(--background-3) rounded-sm">
                                        Current plan
                                    </span>
                                @elseif ($user->isActivePlan() && $user->plan !== 'starter')
                                    <span
                                        class="mt-auto text-center py-2 text-xs text-(--text-muted) border border-(--background-3) rounded-sm opacity-50 cursor-not-allowed">
                                        Active subscription
                                    </span>
                                @else
                                    <a href="{{ route('subscription.checkout', $key) }}"
                                        class="mt-auto text-center py-2 text-xs font-semibold rounded-sm transition-colors
                                        {{ $key === 'premium'
                                            ? 'bg-yellow-400/10 text-yellow-400 border border-yellow-400/20 hover:bg-yellow-400/20'
                                            : 'bg-blue-400/10 text-blue-400 border border-blue-400/20 hover:bg-blue-400/20' }}">
                                        Choose {{ $plan['label'] }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">
                        <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-4">Paid history
                        </h2>

                        @php $payments = $user->subscriptionPayments()->latest()->take(10)->get(); @endphp

                        @if ($payments->isEmpty())
                            <p class="text-sm text-(--text-muted) text-center py-8">No payments yet</p>
                        @else
                            <div class="flex flex-col gap-2">
                                @foreach ($payments as $payment)
                                    <div
                                        class="flex items-center justify-between py-3 border-b border-(--background-3) last:border-0 text-xs">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="font-semibold text-(--text-primary) uppercase">{{ $payment->plan }}</span>
                                            <span
                                                class="text-(--text-muted)">{{ $payment->created_at->format('d.m.Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-(--text-primary) font-semibold">{{ $payment->amount }}
                                                {{ $payment->currency }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                <div x-show="tab === 'support'" x-cloak>
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">
                        <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-6">
                            {{ __('messages.prof-support') }}</h2>
                        <div class="flex flex-col items-center justify-center py-16 gap-3">
                            <x-heroicon-o-lifebuoy class="w-8 h-8 text-(--background-3)" />
                            <p class="text-sm text-(--text-muted)">{{ __('messages.prof-notickets') }}</p>
                            <a href="mailto:support@rent.use"
                                class="mt-2 px-4 py-2 text-xs font-semibold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h) transition-all">
                                {{ __('messages.prof-create-ticket') }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
