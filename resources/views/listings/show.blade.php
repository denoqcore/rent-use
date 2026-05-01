@extends('layouts.layout')

@section('title', 'rent.use | ' . $listing->title)

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-4 md:py-6">

        <nav class="text-sm text-gray-400 mb-3">
            <a href="/">Home</a>
            <span class="mx-1">›</span>
            {{ $listing->category->parent->name ?? '' }}
            <span class="mx-1">›</span>
            {{ $listing->category->name }}
        </nav>

        <h1 class="text-lg sm:text-xl md:text-2xl font-semibold leading-snug mb-4">
            {{ $listing->title }}
        </h1>

        <div class="flex flex-col lg:flex-row gap-5 lg:gap-8">

            <div class="flex flex-row gap-2 flex-1 min-w-0">

                <div class="hidden lg:block">
                    <div class="swiper thumbSlider w-18 shrink-0 overflow-hidden" style="height: 420px;">
                        <div class="swiper-wrapper">
                            @foreach ($listing->images as $image)
                                <div
                                    class="swiper-slide w-18! h-18! rounded-xl overflow-hidden cursor-pointer border-2 border-transparent transition-all">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="swiper mainSlider flex-1 min-w-0 relative group overflow-hidden rounded-2xl">
                    <div class="swiper-wrapper">
                        @foreach ($listing->images as $image)
                            <div class="swiper-slide">
                                <div class="flex items-center justify-center bg-gray-50 h-65 sm:h-95 lg:h-155">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                        class="max-w-full max-h-full object-contain">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-button-prev shadow-md ">
                        <x-heroicon-o-arrow-left class="w-6 h-6 shrink-0 opacity-60" />
                    </div>
                    <div class="swiper-button-next shadow-md ">
                        <x-heroicon-o-arrow-right class="w-6 h-6 shrink-0 opacity-60" />
                    </div>
                    <div class="swiper-pagination mt-3 lg:hidden"></div>
                </div>

            </div>

            <div class="lg:w-75 w-full">
                <div class="border border-gray-100 rounded-2xl p-4 lg:p-5">
                    <div class="text-xl font-medium">
                        {{ number_format($listing->price_per_day) }} {{ $listing->currency }}
                        <span class="text-sm font-normal text-gray-400">/ day</span>
                    </div>

                    @if ($listing->price_per_hour)
                        <div class="text-sm text-gray-400 mt-0.5">
                            or {{ number_format($listing->price_per_hour) }} {{ $listing->currency }} / hour
                        </div>
                    @endif

                    <div class="mt-4 flex flex-col gap-3 text-sm">
                        @if ($listing->deposit)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Deposit</span>
                                <span class="font-medium">{{ number_format($listing->deposit) }}
                                    {{ $listing->currency }}</span>
                            </div>
                        @endif

                        @if ($listing->delivery_available)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Delivery</span>
                                @if ($listing->delivery_price)
                                    <span class="font-medium">{{ number_format($listing->delivery_price) }}
                                        {{ $listing->currency }}</span>
                                @else
                                    <span class="font-medium text-green-500">Free</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    @auth
                        @if (auth()->id() !== $listing->user_id)
                            <button
                                class="mt-5 w-full bg-blue-600 hover:bg-blue-700 transition text-white rounded-xl py-3 text-sm font-semibold">
                                Write {{ $listing->user->name }}
                            </button>
                        @else
                            <a href="{{ route('listings.edit', $listing->slug) }}"
                                class="mt-5 block text-center w-full border border-gray-200 rounded-xl py-3 text-sm hover:bg-gray-50 transition">
                                Edit listing
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="mt-5 block text-center w-full border border-gray-200 rounded-xl py-3 text-sm hover:bg-gray-50 transition">
                            Login to contact
                        </a>
                    @endauth

                    @if ($listing->requires_document)
                        <div
                            class="mt-3 flex items-center justify-center gap-1.5 text-xs text-amber-700 bg-amber-50 rounded-full px-3 py-1.5">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 12l2 2 4-4" />
                                <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Document required
                        </div>
                    @endif

                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-center gap-3">
                        @if ($listing->user->avatar)
                            <img src="{{ asset('storage/' . $listing->user->avatar) }}"
                                class="w-10 h-10 rounded-full object-cover shrink-0">
                        @else
                            <div
                                class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-medium text-sm shrink-0">
                                {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="text-sm font-medium">{{ $listing->user->name }}</div>
                            <div class="flex items-center gap-1 text-xs text-gray-400 mt-0.5">
                                @if ($listing->user->is_online)
                                @else
                                    {{ $listing->user->last_seen_at?->diffForHumans() ?? 'Offline' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 flex flex-col gap-8 lg:grid lg:grid-cols-[1fr_300px]">

            <div>
                <h2 class="text-base font-medium mb-3">Description</h2>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $listing->description }}</p>

                <div class="mt-8">
                    <h2 class="text-base font-medium mb-3">Details</h2>
                    <div class="flex flex-col text-sm divide-y divide-gray-100">
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-400">City</span>
                            <span class="font-medium">{{ $listing->city->name }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-400">Category</span>
                            <span class="font-medium">{{ $listing->category->name }}</span>
                        </div>
                        @if ($listing->requires_document)
                            <div class="flex justify-between py-2.5">
                                <span class="text-gray-400">Document</span>
                                <span class="font-medium">Required</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="h-24 lg:hidden"></div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiperThumbs = new Swiper(".thumbSlider", {
            modules: [window.Thumbs],
            direction: "vertical",
            spaceBetween: 10,
            slidesPerView: "auto",
            freeMode: true,
            watchSlidesProgress: true,
            slideToClickedSlide: true,
        });

        const swiperMain = new Swiper(".mainSlider", {
            modules: [window.Navigation, window.Pagination, window.Thumbs],
            slidesPerView: 1,
            spaceBetween: 0,
            grabCursor: true,
            loop: false,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            thumbs: {
                swiper: swiperThumbs,
            },
        });
    });
</script>
