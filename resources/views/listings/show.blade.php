@extends('layouts.layout')

@section('title', 'rent.use | ' . $listing->title)

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-4 md:py-6">

        {{-- Breadcrumbs --}}
        <nav class="text-sm text-gray-400 mb-3">
            {{ $listing->category->parent->name ?? '' }}
            <span class="mx-1">›</span>
            {{ $listing->category->name }}
        </nav>

        {{-- Title --}}
        <h1 class="text-lg sm:text-xl md:text-2xl font-semibold leading-snug mb-4">
            {{ $listing->title }}
        </h1>

        <div class="flex flex-col lg:flex-row gap-5 lg:gap-8">

            {{-- LEFT: SLIDER SECTION --}}
            <div class="flex flex-col lg:flex-row gap-6 flex-1 min-w-0">

                {{-- THUMBS (Desktop only) --}}
                <div class=" hidden lg:block">
                    <div class="swiper thumbSlider w-[72px] h-[420px] shrink-0 overflow-hidden">
                        <div class="swiper-wrapper">
                            @foreach ($listing->images as $image)
                                <div
                                    class="swiper-slide !w-[72px] !h-[72px] rounded-xl overflow-hidden cursor-pointer border-2 border-transparent transition-all">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- MAIN SLIDER --}}
                <div class="swiper mainSlider w-full relative group !overflow-visible lg:!overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach ($listing->images as $image)
                            <div class="swiper-slide">
                                <div class="swiper-slide rounded-2xl overflow-hidden  flex items-center justify-center">
                                    <div class="flex items-center justify-center h-[280px] sm:h-[450px] md:h-[620px]">
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                            class="max-w-full max-h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-button-prev">
                        <x-heroicon-o-arrow-left class="w-6 h-6 shrink-0 opacity-60" />
                    </div>
                    <div class="swiper-button-next">
                        <x-heroicon-o-arrow-right class="w-6 h-6 shrink-0 opacity-60" />
                    </div>
                    <div class="swiper-pagination !static mt-5 lg:hidden"></div>

                </div>
            </div>

            {{-- RIGHT: PRICE CARD --}}
            <div class="lg:w-[300px] w-full">
                <div class=" bg-white border-t p-4 lg:p-5">
                    <div class="text-xl font-medium">
                        {{ number_format($listing->price_per_day) }} {{ $listing->currency }}
                        <span class="text-sm font-normal text-gray-400">/ day</span>

                        @if ($listing->price_per_hour)
                            <div class="text-sm text-gray-400 mt-0.5">
                                or {{ number_format($listing->price_per_hour) }} {{ $listing->currency }} / hour
                            </div>
                        @endif
                    </div>

                    <div class="mt-5">
                        @if ($listing->deposit)
                            <div class="flex justify-between">
                                <span class="text-gray-400">Deposit</span>
                                <span class="font-medium">{{ number_format($listing->deposit) }}
                                    {{ $listing->currency }}</span>
                            </div>
                            <hr class="border-gray-100">
                        @endif
                    </div>

                    <div class="mt-5">
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
                            <button class="mt-4 w-full bg-blue-600 text-white rounded-xl py-3 text-sm font-semibold">
                                Write {{ $listing->user->name }}
                            </button>
                        @else
                            <a href="{{ route('listings.edit', $listing->slug) }}"
                                class="mt-4 block text-center w-full border rounded-xl py-3 text-sm">
                                Edit
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- desc --}}
        <div class="mt-8 grid grid-cols-[1fr_300px] gap-8">

            <div class="flex-col lg:flex-row">
                <div>
                    <h2 class="text-base font-medium mb-3">Description</h2>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $listing->description }}</p>
                </div>

                <div class="mt-20">
                    <h2 class="text-base font-medium mb-3">Details</h2>
                    <div class="flex flex-col text-sm divide-y divide-gray-100">
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-400">City</span>
                            <span class="font-medium">{{ $listing->city }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-400">Category</span>
                            <span class="font-medium">{{ $listing->category->name }}</span>
                        </div>
                        @if ($listing->requires_document)
                            <div class="flex justify-between py-2.5">
                                <span class="text-gray-400">Document</span>
                                <span class="font-medium">Require</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- space for mobile fixed block --}}
        <div class="h-24 lg:hidden"></div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiperThumbs = new Swiper(".thumbSlider", {
            direction: "vertical",
            spaceBetween: 10,
            slidesPerView: "auto",
            freeMode: true,
            watchSlidesProgress: true,
            slideToClickedSlide: true,
        });

        const swiperMain = new Swiper(".mainSlider", {
            modules: [window.Navigation, window.Thumbs],
            slidesPerView: 1.2,
            centeredSlides: true,
            spaceBetween: 12,
            grabCursor: true,
            loop: false,

            breakpoints: {
                1024: {
                    slidesPerView: 1,
                    centeredSlides: false,
                    spaceBetween: 0,
                }
            },
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
