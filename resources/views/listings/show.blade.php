@section('title', 'rent.use | ' . $listing->title)

{{-- Изображения --}}
@if ($listing->images->count())
    <img src="{{ asset('storage/' . $listing->images->first()->path) }}">

    @foreach ($listing->images as $image)
        <img src="{{ asset('storage/' . $image->path) }}">
    @endforeach
@endif

{{-- Инфо --}}
<span>{{ $listing->category->name }}</span>
<h1>{{ $listing->title }}</h1>
<p>{{ $listing->city }}</p>
<p>{{ $listing->description }}</p>

{{-- Цены и детали --}}
<p>Day: {{ $listing->price_per_day }} MDL</p>

@if ($listing->price_per_hour)
    <p>Hour: {{ $listing->price_per_hour }} MDL</p>
@endif

@if ($listing->deposit)
    <p>Deposit: {{ $listing->deposit }} MDL</p>
@endif

@if ($listing->delivery_available)
    <p>Delivery: {{ $listing->delivery_price ?? 'Free' }}</p>
@endif

@if ($listing->requires_document)
    <p>Document required: Yes</p>
@endif

{{-- Владелец --}}
@auth
    <button>Contact {{ $listing->user->name }}</button>
@else
    <a href="/login">Login to contact</a>
@endauth

@if ($listing->user->avatar)
    <img src="{{ asset('storage/' . $listing->user->avatar) }}">
@else
    <span>{{ strtoupper(substr($listing->user->name, 0, 1)) }}</span>
@endif

{{-- Статус онлайн --}}
@if ($listing->user->is_online)
    <span>Online</span>
@else
    <span>Last seen: {{ $listing->user->last_seen_at?->diffForHumans() ?? 'Offline' }}</span>
@endif
