@extends('layouts.layout')
@section('title', 'rent.use | Listings')
@section('content')

    <div class="max-w-6xl pt-35 lg:pt-25 mx-auto px-6 py-10 sm:mt-10">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-black text-(--text-primary)">{{ __('messages.browse') }}</h1>
            <a href="{{ route('search') }}"
                class="flex items-center gap-2 px-3 py-1.5 rounded-sm border border-(--background-3) bg-(--background-2) text-sm text-(--text-muted)">
                <x-heroicon-o-adjustments-horizontal class="w-4 h-4" />
                {{ __('messages.filters') }}
            </a>
        </div>

        @if ($listings->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <x-heroicon-o-inbox class="w-10 h-10 text-(--text-muted) mb-4 opacity-40" />
                <p class="text-sm font-semibold text-(--text-muted)">{{ __('messages.no-results') }}</p>
            </div>
        @else
            <p class="text-xs text-(--text-muted) mb-4">{{ $listings->total() }} {{ __('messages.results') }}</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                @foreach ($listings as $listing)
                    @php
                        $isFavorited = auth()->check() && auth()->user()->favoriteListings->contains($listing->id);
                    @endphp
                    <a href="{{ route('listings.show', $listing->slug) }}"
                        class="relative group rounded-sm border border-(--background-3) bg-(--background-2) overflow-hidden hover:border-(--text-muted) transition-colors duration-200">
                        <div class="h-44 bg-(--background-3)">
                            @if ($listing->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                    alt="{{ $listing->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-heroicon-o-photo class="w-7 h-7 text-(--text-primary)" />
                                </div>
                            @endif
                        </div>
                        <div class="p-3 flex flex-col gap-1">
                            <div
                                class="flex items-center gap-1 text-[10px] text-(--text-muted) tracking-wide font-medium truncate">
                                <span>{{ $listing->category->parent->name ?? '' }}</span>
                                @if ($listing->category->parent)
                                    <span class="opacity-40">/</span>
                                @endif
                                <span>{{ $listing->category->name }}</span>
                            </div>

                            <div class="absolute top-2 right-2">
                                @if ($isFavorited)
                                    <form method="POST" action="{{ route('favorites.destroy', $listing) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 cursor-pointer hover:scale-120 transition-transform duration-200">
                                            <x-heroicon-s-heart class="w-6 h-6 text-red-500" />
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('favorites.store', $listing) }}">
                                        @csrf
                                        <button type="submit"
                                            class="p-1.5 cursor-pointer hover:scale-120 transition-transform duration-200">
                                            <x-heroicon-o-heart class="w-6 h-6 text-white" />
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <x-listing-star :listing="$listing" />

                            <h3 class="text-sm font-bold text-(--text-primary) truncate">{{ $listing->title }}</h3>
                            <div class="flex items-center justify-between pt-2 mt-auto border-t border-(--background-3)">
                                <div class="flex items-center gap-1 text-(--text-muted)">
                                    <x-heroicon-s-map-pin class="w-3 h-3 shrink-0" />
                                    <span class="text-[11px] font-medium truncate">{{ $listing->city->name }}</span>
                                </div>
                                <span class="text-xs font-bold text-(--blackwhite) whitespace-nowrap ml-2">
                                    @if ($listing->price_per_day)
                                        {{ number_format($listing->price_per_day, 0, '.', ' ') }}
                                        {{ $listing->currency }}<span class="text-(--text-muted) font-normal">/day</span>
                                    @elseif ($listing->price_per_hour)
                                        {{ number_format($listing->price_per_hour, 0, '.', ' ') }}
                                        {{ $listing->currency }}<span class="text-(--text-muted) font-normal">/hr</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($listings->hasPages())
                <div class="mt-10">{{ $listings->links() }}</div>
            @endif
        @endif
    </div>

@endsection
