@extends('layouts.layout')

@section('title', 'rent.use | ' . $listing->title)

@section('content')

    <script>
        window.galleryImages = @json($listing->images->pluck('path')->values());
    </script>

    <div class="max-w-6xl mx-auto px-4 pt-4 pb-2 mt-4 lg:mt-15">
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
        @endphp

        <div class="relative rounded-2xl overflow-hidden cursor-pointer group" onclick="openGallery(0)"
            @if ($count === 1) style="height: 420px;"
            @elseif($count === 2) style="display:grid; grid-template-columns: 1fr 1fr; height: 420px; gap: 4px;"
            @elseif($count === 3) style="display:grid; grid-template-columns: 1fr 1fr; grid-template-rows: 210px 210px; gap: 4px;"
            @else style="display:grid; grid-template-columns: 1fr 1fr; grid-template-rows: 220px 220px; gap: 4px;" @endif>

            @foreach ($images->take(5) as $i => $image)
                <div
                    class="overflow-hidden
                    @if ($count === 1) w-full h-full
                    @elseif($count === 2) h-full
                    @elseif($count === 3 && $i === 0) row-span-2
                    @elseif($count >= 4 && $i === 0) row-span-2 @endif
                    @if ($i >= 3 && $count >= 4) hidden md:block @endif">
                    <img src="{{ asset('storage/' . $image->path) }}"
                        class="w-full h-full object-cover transition-opacity duration-200 group-hover:opacity-95"
                        alt="">
                </div>
            @endforeach

            @if ($count > 1)
                <button onclick="openGallery(0); event.stopPropagation();"
                    class="absolute bottom-4 right-4 rounded-xl px-3 py-1.5 text-sm font-medium flex items-center gap-2 shadow-sm transition-colors"
                    style="background: var(--background); border: 1px solid var(--background-3); color: var(--text-primary);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h4v4H4zM14 6h6M14 10h6M4 14h16M4 18h16" />
                    </svg>
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

        <div class="w-full max-w-4xl px-4 flex flex-col items-center">
            <div class="w-full" style="max-height:72vh;">
                <img id="galleryMainImg" src="" alt="" class="w-full h-full object-contain rounded-xl"
                    style="max-height:72vh;">
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
                        class="flex-shrink-0 w-16 h-11 rounded-lg overflow-hidden cursor-pointer transition-all duration-150"
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

            <div class="flex-1 min-w-0">

                <h1 class="text-2xl sm:text-3xl font-bold leading-tight mb-1" style="color: var(--text-primary)">
                    {{ $listing->title }}
                </h1>

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
                            Document required
                        </span>
                    @endif
                </div>

                <div class="h-px mb-6" style="background: var(--background-3)"></div>

                <div class="flex items-center gap-3 mb-6">
                    @if ($listing->user->avatar)
                        <img src="{{ asset('storage/' . $listing->user->avatar) }}"
                            class="w-11 h-11 rounded-full object-cover shrink-0">
                    @else
                        <div class="w-11 h-11 rounded-full flex items-center justify-center font-semibold text-sm shrink-0"
                            style="background: #dbeafe; color: var(--button)">
                            {{ strtoupper(substr($listing->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-semibold" style="color: var(--text-primary)">{{ $listing->user->name }}
                        </p>
                        <p class="text-xs" style="color: var(--text-muted)">
                            @if ($listing->user->is_online)
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block"
                                        style="background: #4ade80"></span>
                                    Online
                                </span>
                            @else
                                {{ $listing->user->last_seen_at?->diffForHumans() ?? 'Offline' }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="h-px mb-6" style="background: var(--background-3)"></div>

                <div class="mb-6">
                    <h2 class="text-base font-semibold mb-3" style="color: var(--text-primary)">Description</h2>
                    <p class="text-sm leading-relaxed whitespace-pre-line" style="color: var(--text-muted)">
                        {{ $listing->description }}</p>
                </div>

                <div class="h-px mb-6" style="background: var(--background-3)"></div>

                <div>
                    <h2 class="text-base font-semibold mb-3" style="color: var(--text-primary)">Details</h2>
                    <div class="grid grid-cols-2 gap-3 text-sm">

                        <div class="flex flex-col gap-0.5">
                            <span class="text-xs uppercase tracking-wide" style="color: var(--text-muted)">City</span>
                            <span class="font-medium"
                                style="color: var(--text-primary)">{{ $listing->city->name }}</span>
                        </div>

                        <div class="flex flex-col gap-0.5">
                            <span class="text-xs uppercase tracking-wide" style="color: var(--text-muted)">Category</span>
                            <span class="font-medium"
                                style="color: var(--text-primary)">{{ $listing->category->name }}</span>
                        </div>

                        @if ($listing->price_per_day)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide" style="color: var(--text-muted)">Price /
                                    day</span>
                                <span class="font-medium" style="color: var(--text-primary)">
                                    {{ number_format($listing->price_per_day) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->price_per_hour)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide" style="color: var(--text-muted)">Price /
                                    hour</span>
                                <span class="font-medium" style="color: var(--text-primary)">
                                    {{ number_format($listing->price_per_hour) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->deposit)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide"
                                    style="color: var(--text-muted)">Deposit</span>
                                <span class="font-medium" style="color: var(--text-primary)">
                                    {{ number_format($listing->deposit) }} {{ $listing->currency }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->delivery_available)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide"
                                    style="color: var(--text-muted)">Delivery</span>
                                <span class="font-medium"
                                    style="color: {{ $listing->delivery_price ? 'var(--text-primary)' : '#16a34a' }}">
                                    {{ $listing->delivery_price ? number_format($listing->delivery_price) . ' ' . $listing->currency : 'Free' }}
                                </span>
                            </div>
                        @endif

                        @if ($listing->requires_document)
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs uppercase tracking-wide"
                                    style="color: var(--text-muted)">Document</span>
                                <span class="font-medium" style="color: #d97706;">Required</span>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="lg:w-[380px] shrink-0">
                <div class="sticky top-24">
                    <div class="rounded-2xl p-6 shadow-lg"
                        style="border: 1px solid var(--background-3); background: var(--background);">

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
                                        <div class="grid grid-cols-2 rounded-xl overflow-hidden mb-3"
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
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
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
                                                <span>{{ number_format($listing->deposit) }}
                                                    {{ $listing->currency }}</span>
                                            </div>
                                            <div class="flex justify-between font-semibold pt-2"
                                                style="color: var(--text-primary); border-top: 1px solid var(--background-3)">
                                                <span>Total</span>
                                                <span
                                                    x-text="(totalPrice + {{ $listing->deposit ?? 0 }}) + ' {{ $listing->currency }}'"></span>
                                            </div>
                                        @endif
                                    </div>

                                    <button type="submit" :disabled="!canSubmit"
                                        class="w-full rounded-xl py-3.5 text-sm font-semibold transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                        style="background: var(--button); color: var(--button-text);"
                                        onmouseover="if(!this.disabled) this.style.background='var(--button-h)'"
                                        onmouseout="this.style.background='var(--button)'">
                                        Request to Book
                                    </button>

                                    @if (session('success'))
                                        <p class="mt-3 text-xs text-center" style="color: #16a34a">
                                            {{ session('success') }}</p>
                                    @endif

                                    @if ($errors->any())
                                        <p class="mt-3 text-xs text-center" style="color: #dc2626">
                                            {{ $errors->first() }}</p>
                                    @endif

                                </form>

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
                                    <p id="contactSuccess" class="hidden mt-2 text-xs text-center" style="color: #16a34a">✓
                                        Message sendedо</p>
                                    <p id="contactError" class="hidden mt-2 text-xs text-center" style="color: #dc2626"></p>
                                </div>
                            @else
                                <a href="{{ route('listings.edit', $listing->slug) }}"
                                    class="block text-center w-full rounded-xl py-3 text-sm font-medium transition-colors"
                                    style="border: 1px solid var(--background-3); color: var(--text-primary)">
                                    Edit listing
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="block text-center w-full rounded-xl py-3.5 text-sm font-semibold transition-colors"
                                style="background: var(--button); color: var(--button-text)">
                                Login to book
                            </a>
                        @endauth

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MOBILE BOTTOM BAR --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 px-4 pt-3 pb-6 flex items-center gap-3 z-50"
        style="background: var(--background); border-top: 1px solid var(--background-3)">
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
        <button class="w-10 h-10 rounded-xl flex items-center justify-center"
            style="border: 1px solid var(--background-3)">
            <x-heroicon-o-heart class="w-5 h-5" style="color: var(--text-muted)" />
        </button>
        <button class="flex-1 text-sm font-semibold rounded-xl py-3"
            style="background: var(--button); color: var(--button-text)">
            Book now
        </button>
    </div>

    <div class="h-20 lg:hidden"></div>

@endsection
