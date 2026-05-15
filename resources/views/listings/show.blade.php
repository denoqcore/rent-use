@extends('layouts.layout')

@section('title', 'rent.use | ' . $listing->title)

@section('content')

    <div class="max-w-6xl mx-auto px-4 pt-4 pb-2 mt-4 lg:mt-15">
        <nav class="flex items-center gap-1.5 text-xs text-gray-400">
            <a href="/" class="hover:text-gray-700 transition-colors">Home</a>
            <span>›</span>
            <span>{{ $listing->category->parent->name ?? '' }}</span>
            @if ($listing->category->parent)
                <span>›</span>
            @endif
            <span>{{ $listing->category->name }}</span>
        </nav>
    </div>

    <div class="w-full max-w-4xl mx-auto flex flex-col gap-1 mb-10">

        <div class="relative group">
            <div class="swiper mainSlider overflow-hidden ">
                <div class="swiper-wrapper">
                    @foreach ($listing->images as $image)
                        <div class="swiper-slide">
                            <div class="aspect-video md:aspect-21/9 w-full p-4 ">
                                <img src="{{ asset('storage/' . $image->path) }}"
                                    class="w-full h-full object-cover rounded-md lg:transition-transform duration-500 group-hover:scale-102"
                                    alt="Property image">
                            </div>
                        </div>
                    @endforeach
                </div>

                <button
                    class="swiper-button-prev !hidden !md:flex !w-10 !h-10 !bg-white/90 !text-black !rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 after:!text-[14px] left-4 border border-gray-200"></button>
                <button
                    class="swiper-button-next !hidden !md:flex !w-10 !h-10 !bg-white/90 !text-black !rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 after:!text-[14px] right-4 border border-gray-200"></button>

                <div class="swiper-pagination !bottom-6"></div>
            </div>
        </div>

        <div class="w-full">
            <div class="swiper thumbSlider">
                <div class="swiper-wrapper">
                    @foreach ($listing->images as $image)
                        <div class="swiper-slide !w-24 !h-16 md:!w-32 md:!h-20 cursor-pointer ml-4">
                            <div class="w-full h-full rounded-md overflow-hidden transition-all duration-200">
                                <img src="{{ asset('storage/' . $image->path) }}"
                                    class="w-full h-full object-cover opacity-70 hover:opacity-100">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <style>
        .mainSlider .swiper-pagination-bullet {
            background: #fff;
            opacity: 0.6;
        }

        .mainSlider .swiper-pagination-bullet-active {
            background: #fff;
            opacity: 1;
        }
    </style>

    <div class="max-w-6xl mx-auto px-4 pb-16">
        <div class="flex flex-col lg:flex-row gap-10 lg:gap-16">

            <div class="flex-1 min-w-0">

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight mb-1">
                    {{ $listing->title }}
                </h1>

                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <span>{{ $listing->category->name }}</span>
                    <span>·</span>
                    <span class="flex items-center gap-1">
                        <x-heroicon-s-map-pin class="w-3.5 h-3.5" />
                        {{ $listing->city->name }}
                    </span>
                    @if ($listing->requires_document)
                        <span>·</span>
                        <span class="flex items-center gap-1 text-amber-600">
                            <x-heroicon-o-identification class="w-3.5 h-3.5" />
                            Document required
                        </span>
                    @endif
                </div>

                <div class="h-px bg-gray-100 mb-6"></div>

                <div class="flex items-center gap-3 mb-6">
                    @if ($listing->user->avatar)
                        <img src="{{ asset('storage/' . $listing->user->avatar) }}"
                            class="w-11 h-11 rounded-full object-cover shrink-0">
                    @else
                        <div
                            class="w-11 h-11 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-semibold text-sm shrink-0">
                            {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $listing->user->name }}</p>
                        <p class="text-xs text-gray-400">
                            @if ($listing->user->is_online)
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>
                                    Online
                                </span>
                            @else
                                {{ $listing->user->last_seen_at?->diffForHumans() ?? 'Offline' }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="h-px bg-gray-100 mb-6"></div>

                <div class="mb-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-3">Description</h2>
                    <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $listing->description }}</p>
                </div>

                <div class="h-px bg-gray-100 mb-6"></div>

                <div>
                    <h2 class="text-base font-semibold text-gray-900 mb-3">Details</h2>
                    <div class="grid grid-cols-2 gap-3 text-sm">

                        <div class="flex flex-col gap-0.5">
                            <span class="text-xs text-gray-400 uppercase tracking-wide">City</span>
                            <span class="font-medium text-gray-900">{{ $listing->city->name }}</span>
                        </div>

                        <div class="flex flex-col gap-0.5">
                            <span class="text-xs text-gray-400 uppercase tracking-wide">Category</span>
                            <span class="font-medium text-gray-900">{{ $listing->category->name }}</span>
                        </div>

                        @if ($listing->price_per_day)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs text-gray-400 uppercase tracking-wide">Price / day</span>
                                <span class="font-medium text-gray-900">
                                    {{ number_format($listing->price_per_day) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->price_per_hour)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs text-gray-400 uppercase tracking-wide">Price / hour</span>
                                <span class="font-medium text-gray-900">
                                    {{ number_format($listing->price_per_hour) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->deposit)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs text-gray-400 uppercase tracking-wide">Deposit</span>
                                <span class="font-medium text-gray-900">
                                    {{ number_format($listing->deposit) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->delivery_available)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs text-gray-400 uppercase tracking-wide">Delivery</span>
                                <span
                                    class="font-medium {{ $listing->delivery_price ? 'text-gray-900' : 'text-green-600' }}">
                                    {{ $listing->delivery_price ? number_format($listing->delivery_price) . ' ' . $listing->currency : 'Free' }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->requires_document)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs text-gray-400 uppercase tracking-wide">Document</span>
                                <span class="font-medium text-amber-600">Required</span>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            <div class="lg:w-[380px] shrink-0">
                <div class="sticky top-24">
                    <div class="border border-gray-200 rounded-2xl p-6 shadow-lg bg-white">

                        {{-- Цена --}}
                        <div class="mb-5">
                            @if ($listing->price_per_day)
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($listing->price_per_day) }}
                                    <span class="text-base font-normal text-gray-500">{{ $listing->currency }} /
                                        day</span>
                                </div>
                            @endif
                            @if ($listing->price_per_hour)
                                <div
                                    class="{{ $listing->price_per_day ? 'text-sm text-gray-500 mt-0.5' : 'text-2xl font-bold text-gray-900' }}">
                                    @if ($listing->price_per_day)
                                        or {{ number_format($listing->price_per_hour) }} {{ $listing->currency }} / hour
                                    @else
                                        {{ number_format($listing->price_per_hour) }}
                                        <span class="text-base font-normal text-gray-500">{{ $listing->currency }} /
                                            hour</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @auth
                            @if (auth()->id() !== $listing->user_id)
                                @php
                                    $initialMode =
                                        $listing->price_per_day && $listing->price_per_hour
                                            ? 'both'
                                            : ($listing->price_per_day
                                                ? 'day'
                                                : 'hour');
                                @endphp

                                <form method="POST" action="{{ route('bookings.store', $listing) }}" x-data="bookingForm(
                                    {{ $listing->price_per_day ?? 0 }},
                                    {{ $listing->price_per_hour ?? 0 }},
                                    {{ json_encode($bookedDates) }},
                                    '{{ $initialMode }}'
                                )"
                                    x-init="init()">
                                    @csrf

                                    @if ($listing->price_per_day && $listing->price_per_hour)
                                        <div class="flex gap-1 p-1 rounded-xl bg-gray-100 mb-4">
                                            <button type="button"
                                                @click="pricingMode = 'day'; startHour = ''; endHour = ''; bookingDate = null; totalPrice = 0; calculate()"
                                                :class="pricingMode === 'day' ?
                                                    'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-400'"
                                                class="flex-1 py-2 text-xs rounded-lg transition-all cursor-pointer">
                                                Per day
                                            </button>
                                            <button type="button"
                                                @click="pricingMode = 'hour'; startDate = null; endDate = null; totalPrice = 0; calculate()"
                                                :class="pricingMode === 'hour' ?
                                                    'bg-white shadow-sm text-gray-900 font-semibold' : 'text-gray-400'"
                                                class="flex-1 py-2 text-xs rounded-lg transition-all cursor-pointer">
                                                Per hour
                                            </button>
                                        </div>
                                    @endif

                                    <input type="hidden" name="pricing_mode" x-bind:value="pricingMode">
                                    <input type="hidden" name="booking_date"
                                        x-bind:value="pricingMode === 'hour' && bookingDate ? bookingDate.toISOString().split(
                                            'T')[0] : ''">
                                    <input type="hidden" name="start_hour"
                                        x-bind:value="pricingMode === 'hour' ? (startHour ?? '') : ''">
                                    <input type="hidden" name="end_hour"
                                        x-bind:value="pricingMode === 'hour' ? (endHour ?? '') : ''">

                                    <div x-show="pricingMode === 'day'">
                                        <div class="grid grid-cols-2 border border-gray-200 rounded-xl overflow-hidden mb-3">
                                            <div class="p-3 border-r border-gray-200">
                                                <label
                                                    class="block text-[10px] font-bold text-gray-700 uppercase tracking-wide mb-1">From</label>
                                                <input type="text" name="start_date" x-ref="startInput" readonly
                                                    placeholder="Add date"
                                                    class="w-full text-sm text-gray-900 placeholder:text-gray-400 bg-transparent border-0 outline-none cursor-pointer p-0">
                                            </div>
                                            <div class="p-3">
                                                <label
                                                    class="block text-[10px] font-bold text-gray-700 uppercase tracking-wide mb-1">To</label>
                                                <input type="text" name="end_date" x-ref="endInput" readonly
                                                    placeholder="Add date"
                                                    class="w-full text-sm text-gray-900 placeholder:text-gray-400 bg-transparent border-0 outline-none cursor-pointer p-0">
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="pricingMode === 'hour'">

                                        <div class="border border-gray-200 rounded-xl overflow-hidden mb-3">
                                            <div class="p-3">
                                                <label
                                                    class="block text-[10px] font-bold text-gray-700 uppercase tracking-wide mb-1">Date</label>
                                                <input type="text" x-ref="hourDateInput" readonly
                                                    placeholder="Select date"
                                                    class="w-full text-sm text-gray-900 placeholder:text-gray-400 bg-transparent border-0 outline-none cursor-pointer p-0">
                                            </div>
                                        </div>

                                        <div x-show="bookingDate" x-transition>
                                            <div
                                                class="grid grid-cols-2 border border-gray-200 rounded-xl overflow-hidden mb-3">
                                                <div class="p-3 border-r border-gray-200">
                                                    <label
                                                        class="block text-[10px] font-bold text-gray-700 uppercase tracking-wide mb-1">From</label>
                                                    <div class="relative">
                                                        <select x-model="startHour" @change="endHour = ''; calculate()"
                                                            class="w-full text-sm text-gray-900 bg-transparent border-0 outline-none cursor-pointer appearance-none p-0 pr-4">
                                                            <option value="" disabled selected class="text-gray-400">— :
                                                                —</option>
                                                            <template x-for="time in allTimeSlots" :key="'s-' + time">
                                                                <option :value="time" x-text="time"></option>
                                                            </template>
                                                        </select>
                                                        <svg class="w-3 h-3 text-gray-400 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    <label
                                                        class="block text-[10px] font-bold text-gray-700 uppercase tracking-wide mb-1">To</label>
                                                    <div class="relative">
                                                        <select x-model="endHour" @change="calculate()" :disabled="!startHour"
                                                            class="w-full text-sm text-gray-900 bg-transparent border-0 outline-none cursor-pointer appearance-none p-0 pr-4 disabled:opacity-40">
                                                            <option value="" disabled selected>— : —</option>
                                                            <template x-for="time in endTimeSlots" :key="'e-' + time">
                                                                <option :value="time" x-text="time"></option>
                                                            </template>
                                                        </select>
                                                        <svg class="w-3 h-3 text-gray-400 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div x-show="bookingDate && startHour && endHour" x-transition
                                                class="flex items-center gap-2 px-3 py-2.5 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-700 font-medium mb-3">
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span x-text="bookingDateFormatted"></span>
                                                <span class="opacity-40">·</span>
                                                <span x-text="startHour + ' – ' + endHour"></span>
                                                <span class="opacity-40">·</span>
                                                <span x-text="hours + ' hr'"></span>
                                            </div>
                                        </div>

                                    </div>

                                    <div x-show="totalPrice > 0" x-cloak
                                        class="border-t border-gray-100 pt-4 mb-4 flex flex-col gap-2 text-sm">
                                        <div class="flex justify-between text-gray-600">
                                            <span
                                                x-text="summaryLabel + ' × ' + (pricingMode === 'day'
                                                ? '{{ number_format($listing->price_per_day ?? 0) }} {{ $listing->currency }}'
                                                : '{{ number_format($listing->price_per_hour ?? 0) }} {{ $listing->currency }}')">
                                            </span>
                                            <span x-text="totalPrice + ' {{ $listing->currency }}'"></span>
                                        </div>
                                        @if ($listing->deposit)
                                            <div class="flex justify-between text-gray-600">
                                                <span>Deposit</span>
                                                <span>{{ number_format($listing->deposit) }} {{ $listing->currency }}</span>
                                            </div>
                                            <div
                                                class="flex justify-between font-semibold text-gray-900 border-t border-gray-100 pt-2">
                                                <span>Total</span>
                                                <span
                                                    x-text="(totalPrice + {{ $listing->deposit ?? 0 }}) + ' {{ $listing->currency }}'"></span>
                                            </div>
                                        @endif
                                    </div>

                                    <button type="submit" :disabled="!canSubmit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors text-white rounded-xl py-3.5 text-sm font-semibold">
                                        Request to Book
                                    </button>

                                    @if (session('success'))
                                        <p class="mt-3 text-xs text-green-600 text-center">{{ session('success') }}</p>
                                    @endif

                                    @if ($errors->any())
                                        <p class="mt-3 text-xs text-red-500 text-center">{{ $errors->first() }}</p>
                                    @endif

                                </form>
                            @else
                                <a href="{{ route('listings.edit', $listing->slug) }}"
                                    class="block text-center w-full border border-gray-200 rounded-xl py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    Edit listing
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="block text-center w-full bg-blue-600 hover:bg-blue-700 transition-colors text-white rounded-xl py-3.5 text-sm font-semibold">
                                Login to book
                            </a>
                        @endauth

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div
        class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 pt-3 pb-6 flex items-center gap-3 z-50">
        <div class="flex-1">
            @if ($listing->price_per_day)
                <div class="text-base font-bold text-gray-900">
                    {{ number_format($listing->price_per_day) }} {{ $listing->currency }}
                </div>
                <div class="text-xs text-gray-400">/ day</div>
            @else
                <div class="text-base font-bold text-gray-900">
                    {{ number_format($listing->price_per_hour) }} {{ $listing->currency }}
                </div>
                <div class="text-xs text-gray-400">/ hr</div>
            @endif
        </div>
        <button class="w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center">
            <x-heroicon-o-heart class="w-5 h-5 text-gray-400" />
        </button>
        <button class="flex-1 bg-blue-600 text-white text-sm font-semibold rounded-xl py-3">
            Book now
        </button>
    </div>

    <div class="h-20 lg:hidden"></div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const swiperThumbs = new Swiper(".thumbSlider", {
            spaceBetween: 12,
            slidesPerView: "auto",
            freeMode: true,
            watchSlidesProgress: true,
        });

        const swiperMain = new Swiper(".mainSlider", {
            modules: [window.Navigation, window.Pagination, window.Thumbs],
            slidesPerView: 1,
            spaceBetween: 10,
            grabCursor: true,

            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },

            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true,
            },

            thumbs: {
                swiper: swiperThumbs,
            },

            speed: 600,
        });
    });
</script>
