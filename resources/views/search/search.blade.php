@extends('layouts.layout')
@section('title', 'rent.use | Browse')
@section('content')

    <div class="max-w-6xl pt-25 mx-auto px-6 py-10 sm:mt-10" x-data="{ filtersOpen: false }">


        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-black text-(--text-primary)">{{ __('messages.browse') }}</h1>
            <div class="lg:hidden flex items-center gap-2">
                <select name="sort" form="filter-form-mobile"
                    onchange="document.getElementById('filter-form-mobile').submit()"
                    class="px-3 py-1.5 rounded-sm text-xs bg-(--background-2) border border-(--background-3) text-(--text-muted) focus:outline-none cursor-pointer">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                        {{ __('messages.sort-latest') }}</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                        {{ __('messages.sort-price-asc') }}</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                        {{ __('messages.sort-price-desc') }}</option>
                </select>
                <button @click="filtersOpen = true"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-sm border border-(--background-3) bg-(--background-2) text-sm text-(--text-muted) cursor-pointer">
                    <x-heroicon-o-adjustments-horizontal class="w-4 h-4" />
                    {{ __('messages.filters') }}
                    @if (request()->hasAny(['category', 'city', 'price_min', 'price_max', 'delivery']))
                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background: var(--button)"></span>
                    @endif
                </button>
            </div>
        </div>


        <div x-show="filtersOpen" x-cloak class="lg:hidden fixed inset-0 z-60 flex flex-col justify-end"
            @keydown.escape.window="filtersOpen = false">

            <div x-show="filtersOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="filtersOpen = false"
                class="absolute inset-0 bg-black/50">
            </div>

            <div x-show="filtersOpen" x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transform transition ease-in duration-200" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full" class="relative z-10 rounded-t-2xl flex flex-col"
                style="background: var(--background-2); max-height: 85vh;">

                <div class="flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full" style="background: var(--background-3)"></div>
                </div>

                <div class="flex items-center justify-between px-5 py-3 shrink-0"
                    style="border-bottom: 1px solid var(--background-3)">
                    <span class="text-sm font-bold" style="color: var(--text-primary)">{{ __('messages.filters') }}</span>
                    <button @click="filtersOpen = false" class="p-1 rounded-lg cursor-pointer"
                        style="color: var(--text-muted)">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 px-5 py-4">
                    <form method="GET" action="{{ route('search') }}" id="filter-form-mobile" class="flex flex-col gap-5">

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-3"
                                style="color: var(--text-muted)">{{ __('messages.category') }}</label>

                            <a href="{{ route('search', array_merge(request()->except('category', 'page'), [])) }}"
                                @click="filtersOpen = false"
                                class="flex items-center px-3 py-2.5 rounded-xl mb-1 text-sm font-medium"
                                style="{{ !request('category') ? 'background: color-mix(in srgb, var(--button) 10%, transparent); color: var(--button)' : 'color: var(--text-muted)' }}">
                                {{ __('messages.all') }}
                            </a>

                            @foreach ($categories as $cat)
                                @php
                                    $isActiveParent = request('category') === $cat->slug;
                                    $isAnyChildActive = $cat->children->contains('slug', request('category'));
                                    $isOpen = $isActiveParent || $isAnyChildActive;
                                @endphp
                                <div x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }" class="mb-1">
                                    <div class="flex items-center">
                                        <a href="{{ route('search', array_merge(request()->except('category', 'page'), ['category' => $cat->slug])) }}"
                                            @click="filtersOpen = false"
                                            class="flex-1 px-3 py-2.5 text-sm font-medium rounded-xl"
                                            style="{{ $isActiveParent ? 'background: color-mix(in srgb, var(--button) 10%, transparent); color: var(--button)' : 'color: var(--text-muted)' }}">
                                            {{ $cat->name }}
                                        </a>
                                        @if ($cat->children->isNotEmpty())
                                            <button type="button" @click="open = !open"
                                                class="p-2.5 cursor-pointer transition-transform duration-200"
                                                :class="open ? 'rotate-180' : ''" style="color: var(--text-muted)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if ($cat->children->isNotEmpty())
                                        <div x-show="open" x-collapse class="ml-4 pl-3 flex flex-col gap-0.5 mb-1"
                                            style="border-left: 2px solid var(--background-3)">
                                            @foreach ($cat->children as $child)
                                                <a href="{{ route('search', array_merge(request()->except('category', 'page'), ['category' => $child->slug])) }}"
                                                    @click="filtersOpen = false"
                                                    class="px-3 py-2 rounded-xl text-xs font-medium"
                                                    style="{{ request('category') === $child->slug ? 'background: color-mix(in srgb, var(--button) 10%, transparent); color: var(--button)' : 'color: var(--text-muted)' }}">
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2"
                                style="color: var(--text-muted)">{{ __('messages.city') }}</label>
                            <select name="city"
                                class="w-full px-3 py-2.5 rounded-xl text-sm focus:outline-none cursor-pointer"
                                style="background: var(--background); border: 1px solid var(--background-3); color: var(--text-primary)">
                                <option value="">{{ __('messages.all-cities') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->slug }}"
                                        {{ request('city') === $city->slug ? 'selected' : '' }}>{{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2"
                                style="color: var(--text-muted)">{{ __('messages.price') }}</label>
                            <div class="flex gap-2">
                                <input type="number" name="price_min" value="{{ request('price_min') }}"
                                    placeholder="{{ __('messages.price-min') }}"
                                    class="w-full px-3 py-2.5 rounded-xl text-sm focus:outline-none"
                                    style="background: var(--background); border: 1px solid var(--background-3); color: var(--text-primary)">
                                <input type="number" name="price_max" value="{{ request('price_max') }}"
                                    placeholder="{{ __('messages.price-max') }}"
                                    class="w-full px-3 py-2.5 rounded-xl text-sm focus:outline-none"
                                    style="background: var(--background); border: 1px solid var(--background-3); color: var(--text-primary)">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2"
                                style="color: var(--text-muted)">{{ __('messages.sort') }}</label>
                            <select name="sort"
                                class="w-full px-3 py-2.5 rounded-xl text-sm focus:outline-none cursor-pointer"
                                style="background: var(--background); border: 1px solid var(--background-3); color: var(--text-primary)">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                                    {{ __('messages.sort-latest') }}</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                    {{ __('messages.sort-oldest') }}</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                    {{ __('messages.sort-price-asc') }}</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                    {{ __('messages.sort-price-desc') }}</option>
                            </select>
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="delivery" value="1"
                                {{ request('delivery') ? 'checked' : '' }} class="w-4 h-4 cursor-pointer"
                                style="accent-color: var(--button)">
                            <span class="text-sm"
                                style="color: var(--text-muted)">{{ __('messages.delivery-available') }}</span>
                        </label>

                    </form>
                </div>

                <div class="px-5 py-4 shrink-0 flex gap-3"
                    style="border-top: 1px solid var(--background-3); padding-bottom: calc(1rem + env(safe-area-inset-bottom))">
                    <a href="{{ route('search') }}" class="flex-1 py-3 text-sm text-center font-medium rounded-xl"
                        style="background: var(--background-3); color: var(--text-muted)">
                        {{ __('messages.reset') }}
                    </a>
                    <button type="submit" form="filter-form-mobile"
                        class="flex-1 py-3 text-sm font-bold rounded-xl cursor-pointer"
                        style="background: var(--button); color: var(--button-text)">
                        {{ __('messages.apply') }}
                    </button>
                </div>
            </div>
        </div>


        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1 min-w-0">
                @if ($listings->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <x-heroicon-o-magnifying-glass class="w-10 h-10 text-(--text-muted) mb-4 opacity-40" />
                        <p class="text-sm font-semibold text-(--text-muted)">{{ __('messages.no-results') }}</p>
                    </div>
                @else
                    <div class="hidden lg:flex items-center justify-between mb-4">
                        <p class="text-xs text-(--text-muted)">{{ $listings->total() }} {{ __('messages.results') }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                        @foreach ($listings as $listing)
                            @php
                                $isFavorited =
                                    auth()->check() && auth()->user()->favoriteListings->contains($listing->id);
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
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Remove favorite"
                                                    class="p-1.5 cursor-pointer transition-transform duration-200 hover:scale-120 ">
                                                    <x-heroicon-s-heart class="w-6 h-6 text-red-500" />
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('favorites.store', $listing) }}">
                                                @csrf
                                                <button type="submit" title="Favorite"
                                                    class="p-1.5 cursor-pointer transition-transform duration-200 hover:scale-120">
                                                    <x-heroicon-o-heart class="w-6 h-6 text-white" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    @if (
                                        $listing->is_boosted &&
                                            $listing->boosted_until?->isFuture() &&
                                            in_array($listing->user->plan, ['pro', 'premium']) &&
                                            $listing->user->isActivePlan())
                                        <div class="absolute top-2 left-2">
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded-sm bg-yellow-400/15 text-yellow-400">
                                                <x-heroicon-o-star class="w-3 h-3" />
                                                TOP
                                            </span>
                                        </div>
                                    @endif

                                    <h3 class="text-sm font-bold text-(--text-primary) truncate">{{ $listing->title }}
                                    </h3>
                                    <div
                                        class="flex items-center justify-between pt-2 mt-auto border-t border-(--background-3)">
                                        <div class="flex items-center gap-1 text-(--text-muted)">
                                            <x-heroicon-s-map-pin class="w-3 h-3 shrink-0" />
                                            <span
                                                class="text-[11px] font-medium truncate">{{ $listing->city->name }}</span>
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

                    @if ($listings->hasPages())
                        <div class="mt-10">{{ $listings->links() }}</div>
                    @endif
                @endif
            </div>


            <aside class="hidden lg:block lg:w-64 shrink-0">
                <div class="sticky top-28">
                    <form method="GET" action="{{ route('search') }}" id="filter-form" class="flex flex-col gap-5">

                        <div>
                            <label
                                class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">{{ __('messages.search') }}</label>
                            <div
                                class="flex rounded-sm bg-(--background-2) border border-(--background-3) overflow-hidden">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="..."
                                    class="flex-1 px-3 py-2 text-sm bg-transparent text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none">
                                <button type="submit"
                                    class="px-3 py-2 bg-(--button) text-(--button-text) hover:bg-(--button-h) cursor-pointer">
                                    <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">{{ __('messages.category') }}</label>
                            <div class="flex flex-col gap-0.5">

                                <a href="{{ route('search', array_merge(request()->except('category', 'page'), [])) }}"
                                    class="px-3 py-2 rounded-sm text-sm {{ !request('category') ? 'bg-(--button)/10 text-(--button) font-semibold' : 'text-(--text-muted) hover:bg-(--background-2) hover:text-(--text-primary)' }}">
                                    {{ __('messages.all') }}
                                </a>

                                @foreach ($categories as $cat)
                                    @php
                                        $isActiveParent = request('category') === $cat->slug;
                                        $isAnyChildActive = $cat->children->contains('slug', request('category'));
                                        $isOpen = $isActiveParent || $isAnyChildActive;
                                    @endphp
                                    <div x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }">
                                        <div
                                            class="flex items-center rounded-sm {{ $isActiveParent || $isAnyChildActive ? 'bg-(--button)/10' : '' }}">
                                            <a href="{{ route('search', array_merge(request()->except('category', 'page'), ['category' => $cat->slug])) }}"
                                                class="flex-1 px-3 py-2 text-sm {{ $isActiveParent ? 'text-(--button) font-semibold' : ($isAnyChildActive ? 'text-(--button)/70 font-medium' : 'text-(--text-muted) hover:text-(--text-primary)') }}">
                                                {{ $cat->name }}
                                            </a>
                                            @if ($cat->children->isNotEmpty())
                                                <button type="button" @click="open = !open"
                                                    class="px-2 py-2 cursor-pointer transition-transform duration-200 text-(--text-muted) hover:text-(--text-primary)"
                                                    :class="open ? 'rotate-180' : ''">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                        @if ($cat->children->isNotEmpty())
                                            <div x-show="open" x-collapse
                                                class="ml-3 border-l border-(--background-3) pl-2 flex flex-col gap-0.5 mt-0.5 mb-1">
                                                @foreach ($cat->children as $child)
                                                    <a href="{{ route('search', array_merge(request()->except('category', 'page'), ['category' => $child->slug])) }}"
                                                        class="px-3 py-1.5 rounded-sm text-xs {{ request('category') === $child->slug ? 'bg-(--button)/10 text-(--button) font-semibold' : 'text-(--text-muted) hover:bg-(--background-2) hover:text-(--text-primary)' }}">
                                                        {{ $child->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">{{ __('messages.city') }}</label>
                            <select name="city" onchange="this.form.submit()"
                                class="w-full px-3 py-2 rounded-sm text-sm bg-(--background-2) border border-(--background-3) text-(--text-primary) focus:outline-none cursor-pointer">
                                <option value="">{{ __('messages.all-cities') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->slug }}"
                                        {{ request('city') === $city->slug ? 'selected' : '' }}>{{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">{{ __('messages.price') }}</label>
                            <div class="flex gap-2">
                                <input type="number" name="price_min" value="{{ request('price_min') }}"
                                    placeholder="{{ __('messages.price-min') }}"
                                    class="w-full px-3 py-2 rounded-sm text-sm bg-(--background-2) border border-(--background-3) text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none">
                                <input type="number" name="price_max" value="{{ request('price_max') }}"
                                    placeholder="{{ __('messages.price-max') }}"
                                    class="w-full px-3 py-2 rounded-sm text-sm bg-(--background-2) border border-(--background-3) text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none">
                            </div>
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="delivery" value="1" onchange="this.form.submit()"
                                {{ request('delivery') ? 'checked' : '' }}
                                class="w-4 h-4 rounded accent-(--button) cursor-pointer">
                            <span
                                class="text-sm text-(--text-muted) group-hover:text-(--text-primary)">{{ __('messages.delivery-available') }}</span>
                        </label>

                        <div>
                            <label
                                class="block text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-2">{{ __('messages.sort') }}</label>
                            <select name="sort" onchange="this.form.submit()"
                                class="w-full px-3 py-2 rounded-sm text-sm bg-(--background-2) border border-(--background-3) text-(--text-primary) focus:outline-none cursor-pointer">
                                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>
                                    {{ __('messages.sort-latest') }}</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                                    {{ __('messages.sort-oldest') }}</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                    {{ __('messages.sort-price-asc') }}</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                    {{ __('messages.sort-price-desc') }}</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 py-2 text-sm font-semibold bg-(--button) text-(--button-text) rounded-sm hover:bg-(--button-h) cursor-pointer">
                                {{ __('messages.apply') }}
                            </button>
                            <a href="{{ route('search') }}"
                                class="flex-1 py-2 text-sm text-center text-(--text-muted) hover:text-(--text-primary) bg-(--background-2) border border-(--background-3) rounded-sm">
                                {{ __('messages.reset') }}
                            </a>
                        </div>

                    </form>
                </div>
            </aside>

        </div>
    </div>

@endsection
