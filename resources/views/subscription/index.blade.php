@extends('layouts.layout')
@section('title', 'rent.use | Pricing')
@section('content')

    <div class="max-w-5xl pt-40 lg:pt-38 mx-auto px-6 py-40 sm:mt-10">

        <div class="text-center mb-12">
            <h1 class="text-3xl font-black text-(--text-primary) mb-2">
                {{ __('messages.pricing-title') ?? 'Choose your plan' }}</h1>
            @if ($currentPlan && $currentPlan !== 'starter')
                <p class="text-sm text-(--text-muted)">
                    {{ __('messages.current-plan') ?? 'Current plan' }}:
                    <span class="font-bold text-(--button)">{{ strtoupper($currentPlan) }}</span>
                    @if ($expiresAt)
                        · {{ __('messages.expires') ?? 'expires' }} {{ $expiresAt->format('d.m.Y') }}
                    @endif
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($plans as $key => $plan)
                @php $isCurrent = $currentPlan === $key; @endphp
                <div
                    class="relative rounded-sm border {{ $isCurrent ? 'border-(--button)' : 'border-(--background-3)' }} bg-(--background-2) p-8 flex flex-col">
                    @if ($isCurrent)
                        <span
                            class="absolute -top-3 left-8 px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full bg-(--button) text-(--button-text)">
                            {{ __('messages.active') ?? 'Active' }}
                        </span>
                    @endif

                    <h2 class="text-xl font-black text-(--text-primary) mb-1">{{ $plan['label'] }}</h2>
                    <p class="text-3xl font-black text-(--text-primary) mb-6">
                        {{ $plan['price'] }} {{ $plan['currency'] }}
                        <span class="text-sm font-normal text-(--text-muted)">/ {{ $plan['duration'] }}
                            {{ __('messages.days') ?? 'days' }}</span>
                    </p>

                    <ul class="flex flex-col gap-3 mb-8 flex-1">
                        @foreach ($plan['features'] as $feature)
                            <li class="flex items-center gap-2 text-sm text-(--text-muted)">
                                <x-heroicon-o-check class="w-4 h-4 text-(--button) shrink-0" />
                                {{ __('messages.' . $feature) }}
                            </li>
                        @endforeach
                    </ul>

                    @auth
                        @if ($isCurrent)
                            <form method="POST" action="{{ route('subscription.cancel') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 text-sm font-bold rounded-sm border border-(--background-3) text-(--text-muted) hover:bg-(--background-3) cursor-pointer">
                                    {{ __('messages.cancel-subscription') ?? 'Cancel' }}
                                </button>
                            </form>
                        @elseif (config('app.demo_mode'))
                            <form method="POST" action="{{ route('subscription.demo-checkout', $key) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 text-sm font-bold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h) cursor-pointer">
                                    {{ __('messages.activate-demo-plan') ?? 'Activate demo plan' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('subscription.checkout', $key) }}"
                                class="w-full text-center py-3 text-sm font-bold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h)">
                                {{ __('messages.subscribe') ?? 'Subscribe' }}
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="w-full text-center py-3 text-sm font-bold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h)">
                            {{ __('messages.get-started') }}
                        </a>
                    @endauth
                </div>
            @endforeach
        </div>

    </div>

@endsection
