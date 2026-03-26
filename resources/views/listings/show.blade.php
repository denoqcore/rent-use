@extends('layouts.layout')

@section('title', 'rent.use | ' . $listing->title)

@section('content')

    @if ($listing->images->count())
        <img
            src="{{ asset('storage/' . $listing->images->firstWhere('is_main', true)?->path ?? $listing->images->first()->path) }}">

        @foreach ($listing->images as $image)
            <img src="{{ asset('storage/' . $image->path) }}">
        @endforeach
    @endif

    <span>{{ $listing->category->parent->name ?? $listing->category->name }} / {{ $listing->category->name }}</span>
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

@endsection
