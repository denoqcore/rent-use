@extends('layouts.layout')

@section('title', 'Оплата · ' . $plan['label'])

@section('content')
    <section class="min-h-[calc(100vh-72px)] w-full pt-40 py-12 bg-(--background)">
        <div class="max-w-md mx-auto px-6">

            <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">

                <h1 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-6">Subs</h1>

                <div
                    class="flex items-center justify-between p-4 rounded-sm bg-(--background) border border-(--background-3) mb-6">
                    <div>
                        <span
                            class="text-sm font-bold
                        {{ $planKey === 'premium' ? 'text-yellow-400' : 'text-blue-400' }}">
                            {{ $plan['label'] }}
                        </span>
                        <p class="text-xs text-(--text-muted) mt-0.5">for 30 days</p>
                    </div>
                    <span class="text-lg font-black text-(--text-primary)">{{ $plan['price'] }}
                        {{ $plan['currency'] }}</span>
                </div>
                <ul class="flex flex-col gap-2 mb-6">
                    @foreach ($plan['features'] as $feature)
                        <li class="flex items-center gap-2 text-xs text-(--text-muted)">
                            <x-heroicon-o-check class="w-3.5 h-3.5 text-green-400 shrink-0" />
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>

                <div class="flex flex-col gap-3 mb-6" x-data="{ cardNumber: '', expiry: '', cvv: '', name: '' }">

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-(--text-muted)">Number card</label>
                        <input type="text" placeholder="0000 0000 0000 0000" maxlength="19" x-model="cardNumber"
                            @input="cardNumber = cardNumber.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim()"
                            class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1">
                            <label class="text-xs text-(--text-muted)">Expired</label>
                            <input type="text" placeholder="MM/YY" maxlength="5" x-model="expiry"
                                @input="expiry = expiry.replace(/\D/g,'').replace(/^(\d{2})(\d)/,'$1/$2')"
                                class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-xs text-(--text-muted)">CVV</label>
                            <input type="password" placeholder="•••" maxlength="3" x-model="cvv"
                                class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-(--text-muted)">Name card/label>
                            <input type="text" placeholder="IVAN IVANOV" x-model="name"
                                @input="name = name.toUpperCase()"
                                class="bg-(--background) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                    </div>
                </div>

                <form method="POST" action="{{ route('subscription.mock-pay', $payment) }}">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 text-sm font-bold rounded-sm transition-colors cursor-pointer
                    {{ $planKey === 'premium'
                        ? 'bg-yellow-400/10 text-yellow-400 border border-yellow-400/20 hover:bg-yellow-400/20'
                        : 'bg-blue-400/10 text-blue-400 border border-blue-400/20 hover:bg-blue-400/20' }}">
                        Оплатить {{ $plan['price'] }} {{ $plan['currency'] }}
                    </button>
                </form>

                <a href="{{ route('profile') }}#subscription"
                    class="mt-3 block text-center text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors">
                    Cancel
                </a>

                <p class="mt-4 text-center text-[11px] text-(--text-muted) opacity-50">
                    This is a test payment - money will not be debited.
                </p>
            </div>
        </div>
    </section>
@endsection
