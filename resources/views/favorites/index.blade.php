@extends('layouts.layout')
@section('title', 'rent.use | Favorites')
@section('content')

    <div class="max-w-6xl pt-35 lg:pt-25 mx-auto px-6 py-10 sm:mt-10">

        <h1 class="text-2xl font-black text-(--text-primary) mb-6">{{ __('messages.favorites') ?? 'Favorites' }}</h1>

        @if ($favorites->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <x-heroicon-o-heart class="w-10 h-10 text-(--text-muted) mb-4 opacity-40" />
                <p class="text-sm font-semibold text-(--text-muted)">{{ __('messages.no-favorites') ?? 'No favorites yet' }}
                </p>
                <a href="{{ route('search') }}" class="mt-4 text-sm text-(--button) font-semibold hover:underline">
                    {{ __('messages.browse') }} →
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                @foreach ($favorites as $listing)
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
                            <div class="absolute top-2 right-2">
                                <form method="POST" action="{{ route('favorites.destroy', $listing) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 cursor-pointer hover:scale-120 transition-transform duration-200">
                                        <x-heroicon-s-heart class="w-6 h-6 text-red-500" />
                                    </button>
                                </form>
                            </div>

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

            @if ($favorites->hasPages())
                <div class="mt-10">{{ $favorites->links() }}</div>
            @endif
        @endif
    </div>

@endsection
