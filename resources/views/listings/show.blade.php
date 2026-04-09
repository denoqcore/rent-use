@extends('layouts.layout')

@section('title', 'rent.use | ' . $listing->title)

@section('content')
    <div class="max-w-6xl mx-auto p-4">
        <div class="mb-4">
            <nav class="text-sm text-blue-600 mb-2">
                {{ $listing->category->parent->name ?? '' }} / {{ $listing->category->name }}
            </nav>
            <h1 class="text-3xl font-bold">{{ $listing->title }}</h1>
        </div>

        <div class="flex gap-3 h-125">
            <div class="swiper thumbSlider w-24 shrink-0">
                <div class="swiper-wrapper flex flex-col">
                    @foreach ($listing->images as $image)
                        <div
                            class="swiper-slide aspect-square rounded-lg overflow-hidden cursor-pointer border-2 border-transparent">
                            <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="swiper mainSlider flex-1 bg-gray-100 rounded-2xl overflow-hidden relative group">
                <div class="swiper-wrapper">
                    @foreach ($listing->images as $image)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-contain mx-auto">
                        </div>
                    @endforeach
                </div>

                <div class="swiper-button-next  opacity-0 group-hover:opacity-100 transition">
                </div>
                <div class="swiper-button-prev  opacity-0 group-hover:opacity-100 transition">
                </div>
            </div>
        </div>

        <h1>{{ $listing->title }}</h1>
        <p>{{ $listing->city }}</p>
        <p>{{ $listing->description }}</p>

        <p>Day: {{ number_format($listing->price_per_day) }} {{ $listing->currency }}</p>

        @if ($listing->price_per_hour)
            <p>Hour: {{ number_format($listing->price_per_hour) }} {{ $listing->currency }}</p>
        @endif

        @if ($listing->deposit)
            <p>Deposit: {{ number_format($listing->deposit) }} {{ $listing->currency }}</p>
        @endif

        @if ($listing->delivery_available)
            <p>Delivery:
                {{ $listing->delivery_price ? number_format($listing->delivery_price) . ' ' . $listing->currency : 'Free' }}
            </p>
        @endif

        @if ($listing->requires_document)
            <p>Document required</p>
        @endif

        @if ($listing->user->avatar)
            <img src="{{ asset('storage/' . $listing->user->avatar) }}">
        @else
            <span>{{ strtoupper(substr($listing->user->name, 0, 1)) }}</span>
        @endif

        <span>{{ $listing->user->name }}</span>

        @if ($listing->user->is_online)
            <span>Online</span>
        @else
            <span>Last seen: {{ $listing->user->last_seen_at?->diffForHumans() ?? 'Offline' }}</span>
        @endif

        @auth
            @if (auth()->id() !== $listing->user_id)
                <button>Contact {{ $listing->user->name }}</button>
            @else
                <a href="{{ route('listings.edit', $listing->slug) }}">Edit listing</a>
            @endif
        @else
            <a href="{{ route('login') }}">Login to contact</a>
        @endauth
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const swiperThumbs = new Swiper(".thumbSlider", {
            direction: "vertical",
            spaceBetween: 10,
            slidesPerView: 5,
            freeMode: true,
            watchSlidesProgress: true,
        });

        const swiperMain = new Swiper(".mainSlider", {
            modules: [window.Navigation, window.Thumbs],
            spaceBetween: 30,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: swiperThumbs,
            },
        });
    });
</script>
