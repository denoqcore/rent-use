@extends('layouts.layout')

@section('title', 'rent.use | ' . $listing->title)

@section('content')

    <script>
        window.galleryImages = @json($listing->images->pluck('path')->values());
    </script>

    <div class="max-w-6xl pt-25 mx-auto px-4 pb-2 mt-4 lg:mt-15">
        <nav class="flex items-center gap-1.5 text-xs" style="color: var(--text-muted)">
            <a href="/" class="hover:opacity-70 transition-opacity" style="color: var(--text-muted)">Home</a>
            <span>›</span>
            <span>{{ $listing->category->parent->name ?? '' }}</span>
            @if ($listing->category->parent)
                <span>›</span>
            @endif
            <span>{{ $listing->category->name }}</span>
        </nav>
    </div>

    {{-- GALLERY --}}
    <div class="max-w-6xl mx-auto px-4 mb-8">

        @php
            $images = $listing->images;
            $count = $images->count();
            $display = $count === 3 ? $images->take(2) : $images->take(5);
        @endphp

        <div class="relative rounded-2xl overflow-hidden cursor-pointer group" onclick="openGallery(0)"
            @if ($count === 1) style="height: 420px;"
        @elseif($count === 2) style="display:grid; grid-template-columns: 1fr 1fr; height: 420px; gap: 4px;"
        @elseif($count === 3) style="display:grid; grid-template-columns: 1fr 1fr; height: 420px; gap: 4px;"
        @else style="display:grid; grid-template-columns: 1fr 1fr; grid-template-rows: 220px 220px; gap: 4px;" @endif>

            @foreach ($display as $i => $image)
                @if ($i >= 3)
                    @continue
                @endif
                <div
                    class="overflow-hidden
                @if ($count === 1) w-full h-full
                @elseif($count === 2)
                @elseif($count === 3 && $i === 0)
                @elseif($count >= 4 && $i === 0) row-span-2 @endif">
                    <img src="{{ asset('storage/' . $image->path) }}"
                        class="w-full h-full object-cover transition-opacity duration-200 group-hover:opacity-95"
                        alt="">
                </div>
            @endforeach

            @if ($count > 1)
                <button onclick="openGallery(0); event.stopPropagation();"
                    class="absolute bottom-4 right-4 rounded-xl px-3 py-1.5 text-md font-medium flex items-center gap-2 shadow-sm transition-colors"
                    style="background: var(--background); color: var(--text-primary);">
                    ({{ $count }})
                </button>
            @endif
        </div>
    </div>

    {{-- GALLERY MODAL --}}
    <div id="galleryModal" class="fixed inset-0 z-50 hidden flex-col items-center justify-center"
        style="background: rgba(0,0,0,0.9);" onclick="if(event.target===this) closeGallery()">

        <button onclick="closeGallery()"
            class="absolute top-5 right-5 w-10 h-10 rounded-full flex items-center justify-center transition-colors cursor-pointer"
            style="background: rgba(255,255,255,0.15); color: #fff;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="w-full max-w-6xl px-4 flex flex-col items-center">
            <div class="w-full max-h-[80vh]">
                <img id="galleryMainImg" src="" alt="image" class="w-full h-full object-contain rounded-xl">
            </div>

            @if ($count > 1)
                <div class="flex items-center gap-6 mt-4">
                    <button onclick="galleryGo(-1)"
                        class="w-11 h-11 rounded-full flex items-center justify-center transition-colors cursor-pointer"
                        style="background: rgba(255,255,255,0.15); color: #fff;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <span id="galleryCounter" class="text-sm w-16 text-center" style="color: rgba(255,255,255,0.7);"></span>
                    <button onclick="galleryGo(1)"
                        class="w-11 h-11 rounded-full flex items-center justify-center transition-colors cursor-pointer"
                        style="background: rgba(255,255,255,0.15); color: #fff;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            @endif

            <div id="galleryThumbs" class="flex gap-2 mt-3 overflow-x-auto pb-1 max-w-full" style="scrollbar-width:none;">
                @foreach ($images as $i => $image)
                    <div onclick="galleryGoTo({{ $i }})" data-thumb="{{ $i }}"
                        class="shrink-0 w-16 h-11 rounded-lg overflow-hidden cursor-pointer transition-all duration-150"
                        style="border: 2px solid transparent; opacity: 0.5;">
                        <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-6xl mx-auto px-4 pb-16">
        <div class="flex flex-col lg:flex-row gap-10 lg:gap-16">

            @php
                $isFavorited = auth()->check() && auth()->user()->favoriteListings->contains($listing->id);
            @endphp

            <div class="flex-1 min-w-0">

                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-bold leading-tight mb-1" style="color: var(--text-primary)">
                        {{ $listing->title }}
                    </h1>

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
                                <x-heroicon-o-heart class="w-6 h-6 text-black" />
                            </button>
                        </form>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-sm mb-6" style="color: var(--text-muted)">
                    <span>{{ $listing->category->name }}</span>
                    <span>·</span>
                    <span class="flex items-center gap-1">
                        <x-heroicon-s-map-pin class="w-3.5 h-3.5" />
                        {{ $listing->city->name }}
                    </span>
                    @if ($listing->requires_document)
                        <span>·</span>
                        <span class="flex items-center gap-1" style="color: #d97706;">
                            <x-heroicon-o-identification class="w-3.5 h-3.5" />
                            {{ __('messages.document_req') }}
                        </span>
                    @endif
                </div>

                {{-- <div class="h-px mb-6" style="background: var(--background-3)"></div> --}}

                <div class="h-px mb-6" style="background: var(--background-3)"></div>

                <div class="mb-6">
                    <h2 class="text-base font-semibold mb-3" style="color: var(--text-primary)">
                        {{ __('messages.description') }}</h2>
                    <p class="text-sm leading-relaxed whitespace-pre-line" style="color: var(--text-muted)">
                        {{ $listing->description }}</p>
                </div>

                <div class="h-px mb-6" style="background: var(--background-3)"></div>

                <div>
                    <h2 class="text-base font-semibold mb-3" style="color: var(--text-primary)">
                        {{ __('messages.details') }}</h2>
                    <div class="grid grid-cols-2 gap-3 text-sm">

                        <div class="flex flex-col gap-0.5">
                            <span class="text-xs uppercase tracking-wide"
                                style="color: var(--text-muted)">{{ __('messages.city') }}</span>
                            <span class="font-medium"
                                style="color: var(--text-primary)">{{ $listing->city->name }}</span>
                        </div>

                        <div class="flex flex-col gap-0.5">
                            <span class="text-xs uppercase tracking-wide"
                                style="color: var(--text-muted)">{{ __('messages.category') }}</span>
                            <span class="font-medium"
                                style="color: var(--text-primary)">{{ $listing->category->name }}</span>
                        </div>

                        @if ($listing->price_per_day)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide"
                                    style="color: var(--text-muted)">{{ __('messages.price-day') }}</span>
                                <span class="font-medium" style="color: var(--text-primary)">
                                    {{ number_format($listing->price_per_day) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->price_per_hour)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide"
                                    style="color: var(--text-muted)">{{ __('messages.price-hour') }}</span>
                                <span class="font-medium" style="color: var(--text-primary)">
                                    {{ number_format($listing->price_per_hour) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->deposit)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide"
                                    style="color: var(--text-muted)">{{ __('messages.deposit') }}</span>
                                <span class="font-medium" style="color: var(--text-primary)">
                                    {{ number_format($listing->deposit) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->delivery_available)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide"
                                    style="color: var(--text-muted)">{{ __('messages.delivery') }}</span>
                                <span class="font-medium"
                                    style="color: {{ $listing->delivery_price ? 'var(--text-primary)' : '#16a34a' }}">
                                    {{ $listing->delivery_price ? number_format($listing->delivery_price) . ' ' . $listing->currency : 'Free' }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->requires_document)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide"
                                    style="color: var(--text-muted)">{{ __('messages.document') }}</span>
                                <span class="font-medium" style="color: #d97706;">{{ __('messages.required') }}</span>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="lg:w-95 shrink-0">
                <div class="sticky top-24">
                    <div class="relative rounded-2xl p-6"
                        style="border: 1px solid var(--background-3); background: var(--background);">

                        @php
                            $user = $listing->user;
                            $showStar =
                                $user->isActivePlan() &&
                                ($user->plan === 'premium' ||
                                    ($user->plan === 'pro' &&
                                        $listing->is_boosted &&
                                        $listing->boosted_until?->isFuture()));
                        @endphp
                        @if ($showStar)
                            <div class="absolute top-3 right-3">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded-sm bg-(--bg-star) text-(--star-color)">
                                    <x-heroicon-s-star class="w-4 h-4" />
                                </span>
                            </div>
                        @endif

                        <div class="mb-5">
                            @if ($listing->price_per_day)
                                <div class="text-2xl font-bold" style="color: var(--text-primary)">
                                    {{ number_format($listing->price_per_day) }}
                                    <span class="text-base font-normal"
                                        style="color: var(--text-muted)">{{ $listing->currency }} / day</span>
                                </div>
                            @endif
                            @if ($listing->price_per_hour)
                                <div class="{{ $listing->price_per_day ? 'text-sm mt-0.5' : 'text-2xl font-bold' }}"
                                    style="color: {{ $listing->price_per_day ? 'var(--text-muted)' : 'var(--text-primary)' }}">
                                    @if ($listing->price_per_day)
                                        or {{ number_format($listing->price_per_hour) }} {{ $listing->currency }} / hour
                                    @else
                                        {{ number_format($listing->price_per_hour) }}
                                        <span class="text-base font-normal"
                                            style="color: var(--text-muted)">{{ $listing->currency }} / hour</span>
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

                                <form method="POST" action="{{ route('bookings.store', $listing) }}"
                                    x-data="bookingForm(
                                        {{ $listing->price_per_day ?? 0 }},
                                        {{ $listing->price_per_hour ?? 0 }},
                                        {{ json_encode($bookedDates) }},
                                        '{{ $initialMode }}'
                                    )" x-init="init()">
                                    @csrf

                                    @if ($listing->price_per_day && $listing->price_per_hour)
                                        <div class="flex gap-1 p-1 rounded-xl mb-4" style="background: var(--background-3)">
                                            <button type="button"
                                                @click="pricingMode = 'day'; startHour = ''; endHour = ''; bookingDate = null; totalPrice = 0; calculate()"
                                                :class="pricingMode === 'day' ? 'shadow-sm font-semibold' : 'opacity-50'"
                                                class="flex-1 py-2 text-xs rounded-lg transition-all cursor-pointer"
                                                :style="pricingMode === 'day' ?
                                                    'background: var(--background); color: var(--text-primary)' :
                                                    'color: var(--text-muted)'">
                                                Per day
                                            </button>
                                            <button type="button"
                                                @click="pricingMode = 'hour'; startDate = null; endDate = null; totalPrice = 0; calculate()"
                                                :class="pricingMode === 'hour' ? 'shadow-sm font-semibold' : 'opacity-50'"
                                                class="flex-1 py-2 text-xs rounded-lg transition-all cursor-pointer"
                                                :style="pricingMode === 'hour' ?
                                                    'background: var(--background); color: var(--text-primary)' :
                                                    'color: var(--text-muted)'">
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
                                        <div class="grid grid-cols-2 rounded-md overflow-hidden mb-3"
                                            style="border: 1px solid var(--background-3)">
                                            <div class="p-3" style="border-right: 1px solid var(--background-3)">
                                                <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                                    style="color: var(--text-primary)">From</label>
                                                <input type="text" name="start_date" x-ref="startInput" readonly
                                                    placeholder="Add date"
                                                    class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer p-0"
                                                    style="color: var(--text-primary)">
                                            </div>
                                            <div class="p-3">
                                                <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                                    style="color: var(--text-primary)">To</label>
                                                <input type="text" name="end_date" x-ref="endInput" readonly
                                                    placeholder="Add date"
                                                    class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer p-0"
                                                    style="color: var(--text-primary)">
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="pricingMode === 'hour'">
                                        <div class="rounded-xl overflow-hidden mb-3"
                                            style="border: 1px solid var(--background-3)">
                                            <div class="p-3">
                                                <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                                    style="color: var(--text-primary)">Date</label>
                                                <input type="text" x-ref="hourDateInput" readonly
                                                    placeholder="Select date"
                                                    class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer p-0"
                                                    style="color: var(--text-primary)">
                                            </div>
                                        </div>

                                        <div x-show="bookingDate" x-transition>
                                            <div class="grid grid-cols-2 rounded-xl overflow-hidden mb-3"
                                                style="border: 1px solid var(--background-3)">
                                                <div class="p-3" style="border-right: 1px solid var(--background-3)">
                                                    <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                                        style="color: var(--text-primary)">From</label>
                                                    <div class="relative">
                                                        <select x-model="startHour" @change="endHour = ''; calculate()"
                                                            class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer appearance-none p-0 pr-4"
                                                            style="color: var(--text-primary)">
                                                            <option value="" disabled selected
                                                                style="color: var(--text-muted)">— : —</option>
                                                            <template x-for="time in allTimeSlots" :key="'s-' + time">
                                                                <option :value="time" x-text="time"></option>
                                                            </template>
                                                        </select>
                                                        <svg class="w-3 h-3 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"
                                                            style="color: var(--text-muted)" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                                        style="color: var(--text-primary)">To</label>
                                                    <div class="relative">
                                                        <select x-model="endHour" @change="calculate()"
                                                            :disabled="!startHour"
                                                            class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer appearance-none p-0 pr-4 disabled:opacity-40"
                                                            style="color: var(--text-primary)">
                                                            <option value="" disabled selected>— : —</option>
                                                            <template x-for="time in endTimeSlots" :key="'e-' + time">
                                                                <option :value="time" x-text="time"></option>
                                                            </template>
                                                        </select>
                                                        <svg class="w-3 h-3 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"
                                                            style="color: var(--text-muted)" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div x-show="bookingDate && startHour && endHour" x-transition
                                                class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-medium mb-3"
                                                style="background: #eff6ff; border: 1px solid #bfdbfe; color: var(--button)">
                                                <span x-text="bookingDateFormatted"></span>
                                                <span style="opacity:0.4">·</span>
                                                <span x-text="startHour + ' – ' + endHour"></span>
                                                <span style="opacity:0.4">·</span>
                                                <span x-text="hours + ' hr'"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="totalPrice > 0" x-cloak class="pt-4 mb-4 flex flex-col gap-2 text-sm"
                                        style="border-top: 1px solid var(--background-3)">
                                        <div class="flex justify-between" style="color: var(--text-muted)">
                                            <span
                                                x-text="summaryLabel + ' × ' + (pricingMode === 'day'
                                                ? '{{ number_format($listing->price_per_day ?? 0) }} {{ $listing->currency }}'
                                                : '{{ number_format($listing->price_per_hour ?? 0) }} {{ $listing->currency }}')">
                                            </span>
                                            <span x-text="totalPrice + ' {{ $listing->currency }}'"></span>
                                        </div>
                                        @if ($listing->deposit)
                                            <div class="flex justify-between" style="color: var(--text-muted)">
                                                <span>Deposit</span>
                                                <span>
                                                    {{ number_format($listing->deposit, 0, ',', ' ') }}
                                                    {{ $listing->currency }}
                                                </span>
                                            </div>
                                            <div class="flex gap-2 text-(--text-muted)"">
                                                <span>
                                                    <x-heroicon-o-arrow-path class="w-4 h-4" />
                                                </span>
                                                <p class="text-xs">The deposit will be refunded</p>
                                            </div>
                                        @endif

                                        <div class="flex justify-between items-center pt-3 mt-1 text-base font-semibold"
                                            style="color: var(--text-primary); border-top: 1px solid var(--background-3)">

                                            <span>Total</span>

                                            <span class="text-lg font-bold text-(--text-price)"
                                                x-text="new Intl.NumberFormat('de-DE').format(totalPrice + {{ $listing->deposit ?? 0 }}) + ' {{ $listing->currency }}'">
                                            </span>
                                        </div>
                                    </div>

                                    <button type="submit" :disabled="!canSubmit"
                                        class="w-full rounded-xl py-3.5 text-sm font-semibold transition-colors disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                                        style="background: var(--button); color: var(--button-text);"
                                        onmouseover="if(!this.disabled) this.style.background='var(--button-h)'"
                                        onmouseout="this.style.background='var(--button)'">
                                        Request to Book
                                    </button>

                                    @if (session('success'))
                                        <p class="mt-3 text-xs text-center text-(--status-success)">
                                            {{ session('success') }}</p>
                                    @endif

                                    @if ($errors->any())
                                        <p class="mt-3 text-xs text-center text-(--status-danger)">
                                            {{ $errors->first() }}</p>
                                    @endif

                                </form>

                                <div class="mt-4 pt-4" style="border-top: 1px solid var(--background-3)">
                                    <a href="{{ route('profile.public', $listing->user) }}"
                                        class="flex items-center gap-3 mb-3 group">
                                        @if ($listing->user->avatar)
                                            <img src="{{ asset('storage/' . $listing->user->avatar) }}"
                                                class="w-10 h-10 rounded-full object-cover shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm shrink-0"
                                                style="background: #dbeafe; color: var(--button)">
                                                {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold group-hover:underline truncate"
                                                style="color: var(--text-primary)">
                                                {{ $listing->user->name }}
                                            </p>
                                            @php
                                                $avgRating = $listing->user->averageRating();
                                                $reviewCount = $listing->user->reviewCount();
                                            @endphp
                                            @if ($reviewCount > 0)
                                                <div class="flex items-center gap-1 mt-0.5">
                                                    <x-heroicon-s-star class="w-3 h-3 text-yellow-400 shrink-0" />
                                                    <span class="text-xs font-medium"
                                                        style="color: var(--text-primary)">{{ number_format($avgRating, 1) }}</span>
                                                    <span class="text-xs"
                                                        style="color: var(--text-muted)">({{ $reviewCount }})</span>
                                                </div>
                                            @else
                                                <p class="text-xs mt-0.5" style="color: var(--text-muted)">No reviews yet</p>
                                            @endif
                                        </div>
                                    </a>

                                    @if ($listing->user->phone)
                                        <div x-data="{ revealed: false }">
                                            <button @click="revealed = true" x-show="!revealed"
                                                class="w-full rounded-xl py-2.5 text-sm font-semibold transition-colors cursor-pointer"
                                                style="border: 1px solid var(--background-3); color: var(--text-primary)">
                                                Show phone number
                                            </button>
                                            <a x-show="revealed" x-cloak href="tel:{{ $listing->user->phone }}"
                                                class="flex items-center justify-center gap-2 w-full rounded-xl py-2.5 text-sm font-semibold transition-colors"
                                                style="border: 1px solid var(--background-3); color: var(--text-primary)">
                                                <x-heroicon-o-phone class="w-4 h-4" />
                                                {{ $listing->user->phone ? formatPhone($listing->user->phone) : '—' }}
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div x-data="chatComponent()" class="mt-4">
                                    <div class="h-px mb-4" style="background: var(--background-3)"></div>
                                    <textarea id="contactMessage" rows="3" placeholder="Send message."
                                        class="w-full resize-none rounded-xl px-3.5 py-2.5 text-sm outline-none transition-colors"
                                        style="border: 1px solid var(--background-3); color: var(--text-primary); background: var(--background)"
                                        onfocus="this.style.borderColor='var(--button)'" onblur="this.style.borderColor='var(--background-3)'"></textarea>
                                    <button @click="sendFirstMessage({{ $listing->id }})" id="contactSendBtn"
                                        class="w-full mt-2 rounded-xl py-2.5 text-sm font-semibold transition-colors"
                                        style="background: var(--button); color: var(--button-text)"
                                        onmouseover="this.style.background='var(--button-h)'"
                                        onmouseout="this.style.background='var(--button)'">
                                        Send
                                    </button>
                                    <p id="contactSuccess" class="hidden mt-2 text-xs text-center text-(--status-success)">✓
                                        Message sended</p>
                                    <p id="contactError" class="hidden mt-2 text-xs text-center text-(--status-danger)"></p>
                                </div>
                            @else
                                <a href="{{ route('listings.edit', $listing->slug) }}"
                                    class="block text-center w-full rounded-xl py-3 text-sm font-medium transition-colors cursor-pointer"
                                    style="border: 1px solid var(--background-3); color: var(--text-primary)">
                                    Edit listing
                                </a>
                            @endif

                            @if (auth()->id() === $listing->user_id)
                                @if ($listing->is_boosted && $listing->boosted_until?->isFuture())
                                    <span
                                        class="flex items-center justify-center gap-2 mt-4 text-center w-full rounded-xl py-3 text-sm font-medium transition-colors cursor-pointer"
                                        style="border: 1px solid var(--background-3); color: var(--text-primary)">
                                        <x-heroicon-o-chevron-double-up class="w-4 h-4" />
                                        Available in
                                        {{ now()->diffForHumans($listing->boosted_until, true) }}
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('listings.boost', $listing) }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center justify-center gap-2 mt-4 text-center w-full rounded-xl py-3 text-sm font-medium transition-colors cursor-pointer"
                                            style="border: 1px solid var(--background-3); color: var(--text-primary)">
                                            <x-heroicon-o-chevron-double-up class="w-4 h-4" /> Boost
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="block text-center w-full rounded-xl py-3.5 text-sm font-semibold transition-colors cursor-pointer"
                                style="background: var(--button); color: var(--button-text)">
                                Login to book
                            </a>
                        @endauth

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="lg:hidden fixed bottom-0 left-0 right-0 px-4 pt-3 pb-6 flex items-center gap-3 z-50"
        style="background: var(--background); border-top: 1px solid var(--background-3)" x-data="{ open: false }">

        <div class="flex-1">
            @if ($listing->price_per_day)
                <div class="text-base font-bold" style="color: var(--text-primary)">
                    {{ number_format($listing->price_per_day) }} {{ $listing->currency }}
                </div>
                <div class="text-xs" style="color: var(--text-muted)">/ day</div>
            @else
                <div class="text-base font-bold" style="color: var(--text-primary)">
                    {{ number_format($listing->price_per_hour) }} {{ $listing->currency }}
                </div>
                <div class="text-xs" style="color: var(--text-muted)">/ hr</div>
            @endif
        </div>

        @auth
            @if (auth()->id() !== $listing->user_id)
                <form method="POST"
                    action="{{ $isFavorited ? route('favorites.destroy', $listing) : route('favorites.store', $listing) }}">
                    @csrf
                    @if ($isFavorited)
                        @method('DELETE')
                    @endif
                    <button type="submit" class="w-10 h-10 rounded-xl flex items-center justify-center cursor-pointer"
                        style="border: 1px solid var(--background-3)">
                        @if ($isFavorited)
                            <x-heroicon-s-heart class="w-5 h-5 text-red-500" />
                        @else
                            <x-heroicon-o-heart class="w-5 h-5" style="color: var(--text-muted)" />
                        @endif
                    </button>
                </form>
                <button @click="open = true" class="flex-1 text-sm font-semibold rounded-xl py-3 cursor-pointer"
                    style="background: var(--button); color: var(--button-text)">
                    Book now
                </button>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex-1 text-center text-sm font-semibold rounded-xl py-3 cursor-pointer"
                style="background: var(--button); color: var(--button-text)">
                Book now
            </a>
        @endauth

        {{-- MODAL --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-end justify-center"
            style="background: rgba(0,0,0,0.5)" @click.self="open = false">
            <div class="w-full rounded-t-2xl p-5 pb-8 overflow-y-auto max-h-[90vh]" style="background: var(--background)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform translate-y-full" x-transition:enter-end="transform translate-y-0">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold" style="color: var(--text-primary)">Book</h3>
                    <button @click="open = false"
                        class="w-8 h-8 rounded-full flex items-center justify-center cursor-pointer"
                        style="background: var(--background-3)">
                        <x-heroicon-o-x-mark class="w-4 h-4" style="color: var(--text-muted)" />
                    </button>
                </div>
                <form method="POST" action="{{ route('bookings.store', $listing) }}" x-data="bookingForm(
                    {{ $listing->price_per_day ?? 0 }},
                    {{ $listing->price_per_hour ?? 0 }},
                    {{ json_encode($bookedDates) }},
                    '{{ $initialMode }}'
                )"
                    x-init="init()">
                    @csrf

                    @if ($listing->price_per_day && $listing->price_per_hour)
                        <div class="flex gap-1 p-1 rounded-xl mb-4" style="background: var(--background-3)">
                            <button type="button"
                                @click="pricingMode = 'day'; startHour = ''; endHour = ''; bookingDate = null; totalPrice = 0; calculate()"
                                :class="pricingMode === 'day' ? 'shadow-sm font-semibold' : 'opacity-50'"
                                class="flex-1 py-2 text-xs rounded-lg transition-all cursor-pointer"
                                :style="pricingMode === 'day' ?
                                    'background: var(--background); color: var(--text-primary)' :
                                    'color: var(--text-muted)'">
                                Per day
                            </button>
                            <button type="button"
                                @click="pricingMode = 'hour'; startDate = null; endDate = null; totalPrice = 0; calculate()"
                                :class="pricingMode === 'hour' ? 'shadow-sm font-semibold' : 'opacity-50'"
                                class="flex-1 py-2 text-xs rounded-lg transition-all cursor-pointer"
                                :style="pricingMode === 'hour' ?
                                    'background: var(--background); color: var(--text-primary)' :
                                    'color: var(--text-muted)'">
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
                        <div class="grid grid-cols-2 rounded-md overflow-hidden mb-3"
                            style="border: 1px solid var(--background-3)">
                            <div class="p-3" style="border-right: 1px solid var(--background-3)">
                                <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                    style="color: var(--text-primary)">From</label>
                                <input type="text" name="start_date" x-ref="startInput" readonly
                                    placeholder="Add date"
                                    class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer p-0"
                                    style="color: var(--text-primary)">
                            </div>
                            <div class="p-3">
                                <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                    style="color: var(--text-primary)">To</label>
                                <input type="text" name="end_date" x-ref="endInput" readonly placeholder="Add date"
                                    class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer p-0"
                                    style="color: var(--text-primary)">
                            </div>
                        </div>
                    </div>

                    <div x-show="pricingMode === 'hour'">
                        <div class="rounded-xl overflow-hidden mb-3" style="border: 1px solid var(--background-3)">
                            <div class="p-3">
                                <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                    style="color: var(--text-primary)">Date</label>
                                <input type="text" x-ref="hourDateInput" readonly placeholder="Select date"
                                    class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer p-0"
                                    style="color: var(--text-primary)">
                            </div>
                        </div>

                        <div x-show="bookingDate" x-transition>
                            <div class="grid grid-cols-2 rounded-xl overflow-hidden mb-3"
                                style="border: 1px solid var(--background-3)">
                                <div class="p-3" style="border-right: 1px solid var(--background-3)">
                                    <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                        style="color: var(--text-primary)">From</label>
                                    <div class="relative">
                                        <select x-model="startHour" @change="endHour = ''; calculate()"
                                            class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer appearance-none p-0 pr-4"
                                            style="color: var(--text-primary)">
                                            <option value="" disabled selected style="color: var(--text-muted)">— :
                                                —</option>
                                            <template x-for="time in allTimeSlots" :key="'s-' + time">
                                                <option :value="time" x-text="time"></option>
                                            </template>
                                        </select>
                                        <svg class="w-3 h-3 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"
                                            style="color: var(--text-muted)" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <label class="block text-[10px] font-bold uppercase tracking-wide mb-1"
                                        style="color: var(--text-primary)">To</label>
                                    <div class="relative">
                                        <select x-model="endHour" @change="calculate()" :disabled="!startHour"
                                            class="w-full text-sm bg-transparent border-0 outline-none cursor-pointer appearance-none p-0 pr-4 disabled:opacity-40"
                                            style="color: var(--text-primary)">
                                            <option value="" disabled selected>— : —</option>
                                            <template x-for="time in endTimeSlots" :key="'e-' + time">
                                                <option :value="time" x-text="time"></option>
                                            </template>
                                        </select>
                                        <svg class="w-3 h-3 absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none"
                                            style="color: var(--text-muted)" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div x-show="bookingDate && startHour && endHour" x-transition
                                class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-medium mb-3"
                                style="background: #eff6ff; border: 1px solid #bfdbfe; color: var(--button)">
                                <span x-text="bookingDateFormatted"></span>
                                <span style="opacity:0.4">·</span>
                                <span x-text="startHour + ' – ' + endHour"></span>
                                <span style="opacity:0.4">·</span>
                                <span x-text="hours + ' hr'"></span>
                            </div>
                        </div>
                    </div>

                    <div x-show="totalPrice > 0" x-cloak class="pt-4 mb-4 flex flex-col gap-2 text-sm"
                        style="border-top: 1px solid var(--background-3)">
                        <div class="flex justify-between" style="color: var(--text-muted)">
                            <span
                                x-text="summaryLabel + ' × ' + (pricingMode === 'day'
                                                ? '{{ number_format($listing->price_per_day ?? 0) }} {{ $listing->currency }}'
                                                : '{{ number_format($listing->price_per_hour ?? 0) }} {{ $listing->currency }}')">
                            </span>
                            <span x-text="totalPrice + ' {{ $listing->currency }}'"></span>
                        </div>
                        @if ($listing->deposit)
                            <div class="flex justify-between" style="color: var(--text-muted)">
                                <span>Deposit</span>
                                <span>
                                    {{ number_format($listing->deposit, 0, ',', ' ') }}
                                    {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center pt-3 mt-1 text-base font-semibold"
                            style="color: var(--text-primary); border-top: 1px solid var(--background-3)">

                            <span>Total</span>

                            <span class="text-lg font-bold text-(--text-price)"
                                x-text="new Intl.NumberFormat('de-DE').format(totalPrice + {{ $listing->deposit ?? 0 }}) + ' {{ $listing->currency }}'">
                            </span>
                        </div>
                    </div>

                    <button type="submit" :disabled="!canSubmit"
                        class="w-full rounded-xl py-3.5 text-sm font-semibold transition-colors disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
                        style="background: var(--button); color: var(--button-text);"
                        onmouseover="if(!this.disabled) this.style.background='var(--button-h)'"
                        onmouseout="this.style.background='var(--button)'">
                        Request to Book
                    </button>

                    @if (session('success'))
                        <p class="mt-3 text-xs text-center text-(--status-success)">
                            {{ session('success') }}</p>
                    @endif

                    @if ($errors->any())
                        <p class="mt-3 text-xs text-center text-(--status-danger)">
                            {{ $errors->first() }}</p>
                    @endif

                </form>
                <p class="text-sm text-center py-8" style="color: var(--text-muted)">
                    <a href="#sidebar-booking" class="underline" @click="open = false">
                        Scroll up to book
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="h-20 lg:hidden"></div>

@endsection
