@extends('layouts.layout')

@section('title', 'rent.use | ' . $profileUser->name)

@section('content')

    <section class="min-h-[calc(100vh-72px)] w-full pt-44 pb-16 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-24 flex flex-col gap-3">
                    <div
                        class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 flex flex-col items-center text-center gap-3">
                        <div
                            class="w-20 h-20 rounded-sm bg-(--background-3) flex items-center justify-center overflow-hidden">
                            @if ($profileUser->avatar)
                                <img src="{{ asset('storage/' . $profileUser->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl font-black text-(--text-primary)">
                                    {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <p class="text-base font-black text-(--text-primary)">{{ $profileUser->name }}</p>
                            <p class="text-xs text-(--text-muted) mt-1">
                                {{ __('messages.prof-member-since') }} {{ $profileUser->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>
                    @auth
                        @if ($profileUser->phone && auth()->id() !== $profileUser->id)
                            <button x-data="{ shown: false }" @click="shown = true"
                                class="w-full py-2.5 text-sm font-semibold rounded-sm transition-colors cursor-pointer"
                                style="background: var(--button); color: var(--button-text)"
                                x-text="shown ? '{{ $profileUser->phone }}' : '{{ __('messages.prof-phone') }}'">
                            </button>
                        @endif
                    @endauth
                    @auth
                        @if (auth()->id() !== $profileUser->id && $listings->isNotEmpty())
                            <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-4 flex flex-col gap-2">
                                <textarea id="contactMessage" rows="3" placeholder="Write a message..."
                                    class="w-full resize-none rounded-sm px-3 py-2 text-sm outline-none transition-colors bg-(--background) border border-(--background-3) text-(--text-primary)"
                                    onfocus="this.style.borderColor='var(--button)'" onblur="this.style.borderColor='var(--background-3)'"></textarea>

                                <button @click="sendFirstMessage({{ $listings->first()->id }})" id="contactSendBtn"
                                    class="w-full py-2 text-sm font-semibold rounded-sm transition-colors cursor-pointer"
                                    style="background: var(--button); color: var(--button-text)"
                                    onmouseover="this.style.background='var(--button-h)'"
                                    onmouseout="this.style.background='var(--button)'">
                                    Send message
                                </button>
                                <p id="contactSuccess" class="hidden text-xs text-center text-green-500">✓ Message sent</p>
                                <p id="contactError" class="hidden text-xs text-center text-red-400"></p>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="block text-center w-full py-2.5 text-sm font-semibold rounded-sm transition-colors"
                            style="background: var(--button); color: var(--button-text)">
                            Login to contact
                        </a>
                    @endauth

                </aside>
                <div class="flex-1 min-w-0 flex flex-col gap-6">
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                Listings
                                <span class="ml-1.5 text-(--text-primary)">{{ $activeListingsCount }}</span>
                            </h2>
                            @if ($listings->isNotEmpty())
                                <div class="flex items-center gap-2" x-data="{ search: '', category: '' }">
                                    <div class="relative">
                                        <x-heroicon-o-magnifying-glass
                                            class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-(--text-muted)" />
                                        <input type="text" x-model="search" placeholder="Search..."
                                            class="pl-8 pr-3 py-1.5 text-xs rounded-sm border border-(--background-3) bg-(--background) text-(--text-primary) outline-none focus:border-(--text-muted) transition-colors w-36">
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($listings->isEmpty())
                            <div class="flex flex-col items-center justify-center py-16 gap-3">
                                <x-heroicon-o-squares-2x2 class="w-8 h-8 text-(--background-3)" />
                                <p class="text-sm text-(--text-muted)">{{ __('messages.prof-nolisting') }}</p>
                            </div>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($listings as $listing)
                                    <a href="{{ route('listings.show', $listing->slug) }}"
                                        class="group flex flex-col rounded-sm border border-(--background-3) overflow-hidden hover:border-(--text-muted)/30 transition-colors bg-(--background)">
                                        <div class="relative w-full aspect-[4/3] bg-(--background-3) overflow-hidden">
                                            @if ($listing->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <x-heroicon-o-photo class="w-6 h-6 text-(--text-muted) opacity-40" />
                                                </div>
                                            @endif

                                            @auth
                                                @php $isFav = auth()->user()->favoriteListings->contains($listing->id); @endphp
                                                <form method="POST"
                                                    action="{{ $isFav ? route('favorites.destroy', $listing) : route('favorites.store', $listing) }}"
                                                    class="absolute top-2 right-2" @click.prevent="$el.submit()">
                                                    @csrf
                                                    @if ($isFav)
                                                        @method('DELETE')
                                                    @endif
                                                    <button type="submit"
                                                        class="w-7 h-7 rounded-sm flex items-center justify-center transition-colors cursor-pointer"
                                                        style="background: var(--background)"
                                                        onclick="event.stopPropagation(); event.preventDefault(); this.closest('form').submit()">
                                                        @if ($isFav)
                                                            <x-heroicon-s-heart class="w-4 h-4 text-red-500" />
                                                        @else
                                                            <x-heroicon-o-heart class="w-4 h-4 text-(--text-muted)" />
                                                        @endif
                                                    </button>
                                                </form>
                                            @endauth
                                        </div>
                                        <div class="p-3 flex flex-col gap-1">
                                            <p
                                                class="text-sm font-semibold text-(--text-primary) line-clamp-2 group-hover:underline leading-snug">
                                                {{ $listing->title }}
                                            </p>
                                            <p class="text-xs text-(--text-muted)">{{ $listing->city->name }}</p>
                                            <p class="text-sm font-bold text-(--text-price) mt-1">
                                                @if ($listing->price_per_day)
                                                    {{ number_format($listing->price_per_day) }}
                                                    {{ $listing->currency }}<span
                                                        class="text-xs font-normal text-(--text-muted)">/day</span>
                                                @elseif ($listing->price_per_hour)
                                                    {{ number_format($listing->price_per_hour) }}
                                                    {{ $listing->currency }}<span
                                                        class="text-xs font-normal text-(--text-muted)">/hr</span>
                                                @endif
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @if ($listings->hasPages())
                                <div class="mt-6">
                                    {{ $listings->links() }}
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.prof-reviews') }}
                            </h2>
                            @auth
                                @if (auth()->id() !== $profileUser->id)
                                    <button
                                        class="px-3 py-1.5 text-xs font-semibold rounded-sm transition-colors cursor-pointer"
                                        style="background: var(--button); color: var(--button-text)"
                                        onmouseover="this.style.background='var(--button-h)'"
                                        onmouseout="this.style.background='var(--button)'">
                                        Review
                                    </button>
                                @endif
                            @endauth
                        </div>

                        <div class="flex flex-col sm:flex-row gap-8">
                            <div class="flex flex-col items-start gap-1 shrink-0">
                                <p class="text-4xl font-black text-(--text-primary)">0.0</p>
                                <div class="flex items-center gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <x-heroicon-o-star class="w-4 h-4 text-(--background-3)" />
                                    @endfor
                                </div>
                                <p class="text-xs text-(--text-muted) mt-1">0 {{ __('messages.prof-reviews') }}</p>
                            </div>

                            <div class="flex-1 flex flex-col gap-2">
                                @foreach ([5, 4, 3, 2, 1] as $star)
                                    <div class="flex items-center gap-3 text-xs text-(--text-muted)">
                                        <span class="w-12 text-right shrink-0">{{ $star }}
                                            {{ $star === 1 ? 'star' : 'stars' }}</span>
                                        <div class="flex-1 h-1.5 rounded-full bg-(--background-3) overflow-hidden">
                                            <div class="h-full rounded-full bg-(--background-3)" style="width: 0%"></div>
                                        </div>
                                        <span class="w-4 shrink-0">0</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center py-10 gap-2 mt-4 border-t border-(--background-3)">
                            <x-heroicon-o-chat-bubble-left-right class="w-7 h-7 text-(--background-3)" />
                            <p class="text-sm text-(--text-muted)">No reviews yet</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
