@extends('layouts.layout')

@section('title', 'rent.use | Home')

@section('content')

    <section class="relative overflow-hidden border-b border-(--background-3) bg-(--background) pt-10 md:pt-20">

        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-0 -right-16 w-96 h-96 rounded-full opacity-10 bg-(--button) blur-[80px]"></div>
            <div class="absolute -bottom-20 left-1/3 w-64 h-64 rounded-full opacity-10 bg-[#97C459] blur-[80px]"></div>
        </div>

        <div
            class="relative z-10 max-w-6xl mx-auto mt-10 px-6 pt-14 pb-12 grid grid-cols-1 lg:grid-cols-[1.15fr_0.85fr] gap-10 items-center">
            <div class="flex flex-col items-start">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-(--background-3)
                        text-xs font-medium text-(--text-muted) mb-6">

                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/1280px-Flag_of_Moldova.svg.png"
                        alt="moldova" class="h-3 rounded-xs">

                    {{ __('messages.hero-sub-2') }}
                </div>

                <div>
                    <h1
                        class="text-[40px] md:text-[60px] lg:text-[56px] font-black leading-[1.05] tracking-[-1.5px] text-(--text-primary) max-w-xl mb-5 text-balance">
                        {{ __('messages.rent-hero') }}

                        <span class="relative inline-block">
                            {{ __('messages.rent-hero-2') }}
                            <span
                                class="absolute bottom-0 left-0 right-0 h-0.75 rounded-full opacity-70 bg-(--button)"></span>
                        </span>
                    </h1>
                    <p class="text-sm lg:text-base text-(--text-muted) max-w-md leading-relaxed mb-8">
                        {{ __('messages.hero-sub') }}
                    </p>
                    <a href="/search"
                        class="lg:hidden inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-(--button) text-white text-sm font-semibold shadow-sm active:scale-95 transition-all duration-200">
                        {{ __('messages.browse') }}
                    </a>
                </div>
                <div class="hidden lg:block w-full max-w-xl">
                    <form method="GET" action="{{ route('search') }}"
                        class="flex items-center gap-0 rounded-md border border-(--background-3)
                             bg-(--background-2) overflow-hidden transition-all duration-200
                             focus-within:border-(--button)
                             focus-within:shadow-[0_0_0_3px_color-mix(in_srgb,var(--button)_15%,transparent)]">

                        <label for="hero-search" class="sr-only">{{ __('messages.search') }}</label>

                        <div class="pl-4 pr-2 flex items-center shrink-0 text-(--text-muted)">
                            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        </div>

                        <input id="hero-search" type="text" name="q" value="{{ request('q') }}"
                            placeholder="{{ __('messages.search') }}..." autocomplete="off"
                            class="flex-1 min-w-0 bg-transparent text-(--text-primary)
                                  placeholder:text-(--text-muted) text-sm py-3 focus:outline-none">

                        <button type="submit"
                            class="m-1.5 px-4 py-1.5 rounded-md bg-(--button)
                                   text-(--button-text) text-sm font-semibold
                                   hover:bg-(--button-h) active:scale-95 transition-all
                                   cursor-pointer whitespace-nowrap shrink-0">
                            {{ __('messages.search') }}
                        </button>
                    </form>
                </div>

            </div>

            <div class="hidden md:block">
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($categories as $category)
                        <a href="{{ route('search', ['category' => $category->slug]) }}"
                            class="group relative overflow-hidden rounded-md
                              border border-(--background-3)
                              bg-(--background-2)
                              p-4 transition-all duration-200
                              hover:-translate-y-0.5 hover:shadow-sm
                              {{ $loop->last && $loop->count % 2 !== 0 ? 'md:col-span-2' : '' }}">
                            <div
                                class="absolute -top-10 -right-10 w-40 h-40 rounded-full
                                    bg-[rgba(55,138,221,0.08)] blur-3xl opacity-0
                                    group-hover:opacity-100 transition duration-300">
                            </div>
                            <div
                                class="absolute left-0 top-0 h-full w-0.5
                                    bg-linear-to-b from-transparent via-[rgba(55,138,221,0.3)] to-transparent
                                    opacity-0 group-hover:opacity-100 transition">
                            </div>

                            <div class="relative z-10 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">

                                    <div class="relative">

                                        <div
                                            class="absolute inset-0 rounded-xl scale-110 opacity-0
                                                group-hover:opacity-100 bg-[rgba(55,138,221,0.08)]
                                                blur-md transition">
                                        </div>

                                        <div
                                            class="relative w-9 h-9 rounded-xl
                                                bg-(--background)
                                                border border-(--background-3)
                                                flex items-center justify-center
                                                group-hover:border-[rgba(55,138,221,0.3)]
                                                transition">

                                            <x-dynamic-component :component="$category->icon ?? 'heroicon-o-squares-2x2'"
                                                class="w-4 h-4 text-(--text-muted)
                                                   group-hover:text-(--button)
                                                   transition" />
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <p class="text-sm font-semibold text-(--text-primary)">
                                            {{ $category->name }}
                                        </p>
                                        <div
                                            class="mt-1 h-0.5 w-6 bg-(--background-3)
                                                group-hover:w-10
                                                group-hover:bg-[rgba(55,138,221,0.4)]
                                                transition-all duration-300">
                                        </div>
                                    </div>

                                </div>
                                <div
                                    class="text-(--background-3)
                                        group-hover:text-(--text-muted)
                                        group-hover:translate-x-1 transition">
                                    <x-heroicon-o-arrow-up-right class="w-3.5 h-3.5" />
                                </div>

                            </div>
                        </a>
                    @endforeach

                </div>
            </div>

        </div>
    </section>

    <section class="w-full bg-(--background) py-12">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center justify-between mb-6">
                <span class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                    {{ __('messages.latest') }}
                </span>
                <a href="/search" class="flex items-center gap-1 text-xs text-(--text-muted) hover:text-(--text-primary)">
                    {{ __('messages.more') }}
                    <x-heroicon-o-arrow-right class="w-3 h-3" />
                </a>
            </div>

            @if ($listings->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach ($listings as $listing)
                        @php
                            $isFavorited = auth()->check() && auth()->user()->favoriteListings->contains($listing->id);
                        @endphp

                        <div
                            class="group rounded-sm border border-(--background-3) bg-(--background-2) overflow-hidden hover:border-(--text-muted) transition-colors duration-200">
                            <div class="relative h-44 bg-(--background-3)">
                                <a href="{{ route('listings.show', $listing->slug) }}" class="block w-full h-full">
                                    @if ($listing->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                            alt="{{ $listing->title }}"
                                            class="w-full h-full object-cover transition-transform duration-300 ease-in-out">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <x-heroicon-o-photo class="w-7 h-7 text-(--text-primary)" />
                                        </div>
                                    @endif
                                </a>

                                <x-listing-star :listing="$listing" />

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

                                <a href="{{ route('listings.show', $listing->slug) }}">
                                    <h3 class="text-sm font-bold text-(--text-primary) truncate hover:underline">
                                        {{ $listing->title }}
                                    </h3>
                                </a>

                                <div
                                    class="flex items-center justify-between pt-2 mt-auto border-t border-(--background-3)">
                                    <div class="flex items-center gap-1 text-(--text-muted)">
                                        <x-heroicon-s-map-pin class="w-3 h-3 shrink-0" />
                                        <span class="text-[11px] font-medium truncate">{{ $listing->city->name }}</span>
                                    </div>
                                    <span class="text-xs font-black text-(--blackwhite) whitespace-nowrap ml-2">
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
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center gap-6 min-h-[40vh]">
                    <div class="w-full h-px bg-(--background-3)"></div>
                    <div class="flex flex-col items-center text-center">
                        <x-heroicon-o-inbox class="w-10 h-10 text-(--text-muted) mb-3 opacity-40" />
                        <h2 class="text-sm font-semibold text-(--text-primary) mb-1">
                            {{ __('messages.empty-title') }}
                        </h2>
                        <p class="text-(--text-muted) text-xs max-w-xs">
                            {{ __('messages.empty-desc') }}
                        </p>
                    </div>
                    <div class="w-full h-px bg-(--background-3)"></div>
                </div>
            @endif

        </div>
    </section>

    <section class="w-full py-20 bg-(--background-2)">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div>
                    <span
                        class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.benefits') }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-(--text-primary) mt-3 mb-4 leading-tight">
                        {{ __('messages.benefits-title') }}
                    </h2>
                    <p class="text-(--text-muted) text-sm leading-relaxed max-w-sm">{{ __('messages.benefits-desc') }}</p>
                </div>

                <div
                    class="flex flex-col divide-y divide-(--background-3) border border-(--background-3) rounded-sm overflow-hidden select-none">
                    @foreach ([['icon' => 'heroicon-o-square-3-stack-3d', 'title' => __('messages.benefit-1-title'), 'desc' => __('messages.benefit-1-desc')], ['icon' => 'heroicon-o-shield-check', 'title' => __('messages.benefit-2-title'), 'desc' => __('messages.benefit-2-desc')], ['icon' => 'heroicon-o-calendar', 'title' => __('messages.benefit-3-title'), 'desc' => __('messages.benefit-3-desc')]] as $benefit)
                        <div
                            class="group flex items-start gap-4 p-5 bg-(--background-2) hover:bg-(--background) transition-all duration-200">
                            <div
                                class="p-2 rounded-sm bg-(--background-3) group-hover:bg-(--button)/10 transition-all shrink-0">
                                <x-dynamic-component :component="$benefit['icon']"
                                    class="w-4 h-4 text-(--text-muted) group-hover:text-(--button) transition-all" />
                            </div>
                            <div>
                                <p class="text-sm font-bold text-(--text-primary) mb-1">{{ $benefit['title'] }}</p>
                                <p class="text-xs text-(--text-muted) leading-relaxed">{{ $benefit['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    <section class="w-full py-20 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="mb-12">
                <span
                    class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.how-it-works') }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach ([['step' => '.1', 'icon' => 'heroicon-o-magnifying-glass', 'title' => __('messages.step-1-title'), 'desc' => __('messages.step-1-desc')], ['step' => '.2', 'icon' => 'heroicon-o-chat-bubble-left-ellipsis', 'title' => __('messages.step-2-title'), 'desc' => __('messages.step-2-desc')], ['step' => '.3', 'icon' => 'heroicon-o-arrow-path', 'title' => __('messages.step-3-title'), 'desc' => __('messages.step-3-desc')]] as $step)
                    <div
                        class="relative p-6 rounded-sm bg-(--background-2) border border-(--background-3) overflow-hidden">
                        <span
                            class="absolute top-3 right-4 text-5xl font-medium leading-none select-none text-(--button)">{{ $step['step'] }}
                        </span>
                        <div class="mb-6">
                            <x-dynamic-component :component="$step['icon']" class="w-5 h-5 text-(--button)" />
                        </div>
                        <p class="text-sm font-black text-(--text-primary) mb-1.5">{{ $step['title'] }}</p>
                        <p class="text-xs text-(--text-muted) leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <section class="w-full py-16 bg-(--background-2)">
        <div class="max-w-6xl mx-auto px-6">
            <div
                class="grid lg:grid-cols-2 items-center gap-0 rounded-sm overflow-hidden border border-(--background-3) bg-(--background)">

                <div class="px-10 py-14 lg:px-16 lg:py-20">
                    <h2 class="text-3xl md:text-4xl font-black text-(--text-primary) leading-tight mb-3">
                        {{ __('messages.cta-title') }} <span class="text-(--button)">rent.use</span>
                    </h2>
                    <p class="text-sm text-(--text-muted) mb-8 leading-relaxed max-w-sm">{{ __('messages.cta-desc') }}</p>
                    @guest
                        <a href="/login"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold bg-(--button) text-(--button-text) rounded-sm hover:bg-(--button-h) active:scale-95 transition-all">
                            {{ __('messages.get-started') }}
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    @endguest
                    @auth
                        <a href="/"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium border border-(--background-3) text-(--text-primary) rounded-sm hover:bg-(--background-2) transition-all">
                            {{ __('messages.post-listing') }}
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    @endauth
                </div>

                <div
                    class="flex items-center justify-center px-10 py-14 border-t lg:border-t-0 lg:border-l border-(--background-3)">
                    <img src="{{ asset('storage/images/about.png') }}" alt="rent.use"
                        class="w-full max-w-xs object-contain opacity-80">
                </div>

            </div>
        </div>
    </section>

    <section class="w-full py-16 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="mb-8">
                <span
                    class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.reviews-title') }}</span>
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex gap-0.5">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="text-xs text-(--button)">★</span>
                        @endfor
                    </div>
                    <span class="text-sm font-bold text-(--text-primary)">4.9</span>
                    <span class="text-xs text-(--text-muted)">/ 5.0</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach ([['name' => 'Anna K.', 'text' => __('messages.review-1'), 'rating' => 5], ['name' => 'Maxim R.', 'text' => __('messages.review-2'), 'rating' => 5], ['name' => 'Laura M.', 'text' => __('messages.review-3'), 'rating' => 4]] as $review)
                    <div class="flex flex-col gap-3 p-5 rounded-sm bg-(--background-2) border border-(--background-3)">
                        <div class="flex gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="text-xs {{ $i <= $review['rating'] ? 'text-(--button)' : 'text-(--background-3)' }}">★</span>
                            @endfor
                        </div>
                        <p class="text-sm text-(--text-muted) leading-relaxed flex-1">"{{ $review['text'] }}"</p>
                        <div class="flex items-center gap-2 pt-3 border-t border-(--background-3)">
                            <div
                                class="w-6 h-6 rounded-full bg-(--background-3) flex items-center justify-center text-xs font-bold text-(--text-primary)">
                                {{ substr($review['name'], 0, 1) }}</div>
                            <span class="text-xs font-semibold text-(--text-primary)">{{ $review['name'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

@endsection

@push('scripts')
@endpush
