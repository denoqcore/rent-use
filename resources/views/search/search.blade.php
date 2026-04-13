@extends('layouts.layout')
@section('title', 'rent.use | Browse')
@section('content')

    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- Заголовок + счётчик --}}
        <div class="mb-8">
            <h1 class="text-2xl font-black text-(--text-primary)">{{ __('messages.browse') }}</h1>
            <p class="text-sm text-(--text-muted) mt-1">{{ $listings->total() }} {{ __('messages.results') }}</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- ===== SIDEBAR FILTERS ===== --}}
            <aside class="w-full lg:w-64 shrink-0">
                <form method="GET" action="{{ route('search') }}" id="filter-form" class="flex flex-col gap-6">

                    {{-- Поиск --}}
                    <div>
                        <label class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">
                            {{ __('messages.search') }}
                        </label>
                        <div class="flex gap-2 p-1 rounded-lg bg-(--background-2) border border-(--background-3)">
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Camera, car, guitar..."
                                class="flex-1 px-3 py-2 text-sm bg-transparent text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none">
                            <button type="submit"
                                class="px-3 py-2 bg-(--button) text-(--button-text) rounded-md hover:bg-(--button-h) transition-all cursor-pointer">
                                <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    {{-- Категории --}}
                    <div>
                        <label class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">
                            {{ __('messages.category') }}
                        </label>
                        <div class="flex flex-col gap-0.5">
                            <a href="{{ route('search', array_merge(request()->except('category', 'page'), [])) }}"
                                class="px-3 py-2 rounded-sm text-sm transition-all
                            {{ !request('category') ? 'bg-(--button)/10 text-(--button) font-semibold' : 'text-(--text-muted) hover:bg-(--background-2) hover:text-(--text-primary)' }}">
                                {{ __('messages.all') }}
                            </a>
                            @foreach ($categories as $cat)
                                <a href="{{ route('search', array_merge(request()->except('category', 'page'), ['category' => $cat->slug])) }}"
                                    class="px-3 py-2 rounded-sm text-sm transition-all
                                {{ request('category') === $cat->slug ? 'bg-(--button)/10 text-(--button) font-semibold' : 'text-(--text-muted) hover:bg-(--background-2) hover:text-(--text-primary)' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Город --}}
                    <div>
                        <label class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">
                            {{ __('messages.city') }}
                        </label>
                        <select name="city" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-sm text-sm bg-(--background-2) border border-(--background-3) text-(--text-primary) focus:outline-none cursor-pointer">
                            <option value="">{{ __('messages.all-cities') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Цена --}}
                    <div>
                        <label class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">
                            {{ __('messages.price') }}
                        </label>
                        <div class="flex gap-2">
                            <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min"
                                class="w-full px-3 py-2 rounded-sm text-sm bg-(--background-2) border border-(--background-3) text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none">
                            <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Max"
                                class="w-full px-3 py-2 rounded-sm text-sm bg-(--background-2) border border-(--background-3) text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none">
                        </div>
                    </div>

                    {{-- Доставка --}}
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="delivery" value="1" onchange="this.form.submit()"
                            {{ request('delivery') ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-(--button) cursor-pointer">
                        <span class="text-sm text-(--text-muted) group-hover:text-(--text-primary) transition-colors">
                            {{ __('messages.delivery-available') }}
                        </span>
                    </label>

                    {{-- Сортировка --}}
                    <div>
                        <label class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">
                            {{ __('messages.sort') }}
                        </label>
                        <select name="sort" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-sm text-sm bg-(--background-2) border border-(--background-3) text-(--text-primary) focus:outline-none cursor-pointer">
                            <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                                {{ __('messages.sort-latest') }}</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                {{ __('messages.sort-price-asc') }}</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                {{ __('messages.sort-price-desc') }}</option>
                        </select>
                    </div>

                    {{-- Применить / Сбросить --}}
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 py-2 text-sm font-semibold bg-(--button) text-(--button-text) rounded-sm hover:bg-(--button-h) transition-all cursor-pointer">
                            {{ __('messages.apply') }}
                        </button>
                        <a href="{{ route('search') }}"
                            class="flex-1 py-2 text-sm text-center text-(--text-muted) hover:text-(--text-primary) bg-(--background-2) border border-(--background-3) rounded-sm transition-all">
                            {{ __('messages.reset') }}
                        </a>
                    </div>

                </form>
            </aside>

            {{-- ===== LISTINGS GRID ===== --}}
            <div class="flex-1 min-w-0">
                @if ($listings->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <x-heroicon-o-magnifying-glass class="w-10 h-10 text-(--text-muted) mb-4 opacity-40" />
                        <p class="text-sm font-semibold text-(--text-muted)">{{ __('messages.no-results') }}</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                        @foreach ($listings as $listing)
                            <a href="{{ route('listings.show', $listing->slug) }}"
                                class="group rounded-sm border border-(--background-3) bg-(--background-2) overflow-hidden hover:border-(--text-muted) transition-colors duration-200">
                                <div class="h-44 bg-(--background-3)">
                                    @if ($listing->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                            alt="{{ $listing->title }}"
                                            class="w-full h-full object-cover transition-transform duration-300 ease-in-out">
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
                                    <h3 class="text-sm font-bold text-(--text-primary) truncate">{{ $listing->title }}</h3>
                                    <div
                                        class="flex items-center justify-between pt-2 mt-auto border-t border-(--background-3)">
                                        <div class="flex items-center gap-1 text-(--text-muted)">
                                            <x-heroicon-s-map-pin class="w-3 h-3 shrink-0" />
                                            <span class="text-[11px] font-medium truncate">{{ $listing->city }}</span>
                                        </div>
                                        <span class="text-xs font-bold text-(--button) whitespace-nowrap ml-2">
                                            @if ($listing->price_per_day)
                                                {{ number_format($listing->price_per_day, 0, '.', ' ') }}
                                                {{ $listing->currency }}<span
                                                    class="text-(--text-muted) font-normal">/day</span>
                                            @elseif ($listing->price_per_hour)
                                                {{ number_format($listing->price_per_hour, 0, '.', ' ') }}
                                                {{ $listing->currency }}<span
                                                    class="text-(--text-muted) font-normal">/hr</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Пагинация --}}
                    @if ($listings->hasPages())
                        <div class="mt-10">
                            {{ $listings->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

@endsection
