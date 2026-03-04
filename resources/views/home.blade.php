@extends('layouts.layout')

@section('content')
    <section id="vanta-hero" class="min-h-[calc(70vh-72px)] w-full flex items-center py-20 relative">
        <div class="absolute inset-0 bg-linear-to-b from-transparent to-[#121212] pointer-events-none z-10"></div>
        <div class="relative z-20 w-full max-w-4xl mx-auto px-6">

            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-sm text-xs font-medium mb-8 mx-auto justify-center bg-(--background-2) text-(--text-muted) border border-(--background-3)">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/1280px-Flag_of_Moldova.svg.png"
                    alt="moldova" class="h-4">
                {{ __('messages.hero-sub-2') }}
            </div>

            <h1 class="text-3xl md:text-5xl xl:text-6xl font-black text-(--text-primary) mb-8 leading-snug text-center">
                {{ __('messages.rent-hero') }}<br>
                <span x-data="{
                    full: '{{ __('messages.rent-hero-2') }}',
                    displayed: '',
                    index: 0,
                    done: false
                }" x-init="setTimeout(() => {
                    let interval = setInterval(() => {
                        if (index < full.length) {
                            displayed += full[index];
                            index++;
                        } else {
                            done = true;
                            clearInterval(interval);
                        }
                    }, 60)
                }, 300)" class="text-(--text-muted)">
                    <span x-text="displayed"></span><span x-show="!done" class="animate-pulse">|</span>
                </span>
            </h1>

            <p class="text-center text-(--text-muted) text-sm md:text-base mb-10 max-w-md mx-auto">
                {{ __('messages.hero-sub') }}
            </p>

            <div class="max-w-xl mx-auto">
                <div
                    class="rounded-sm p-1.5 flex flex-col md:flex-row gap-2 bg-(--background-2) border border-(--background-3)">
                    <input type="text" name="q" placeholder="Camera, car, guitar..."
                        class="flex-1 px-4 py-2.5 text-sm rounded-sm bg-transparent text-(--text-primary)
                               placeholder:text-(--text-muted) focus:outline-none transition-all">
                    <button type="submit"
                        class="flex items-center justify-center gap-2 px-6 py-2.5 text-sm bg-(--button) text-(--button-text) font-semibold rounded-sm
                               transition-all hover:bg-(--button-h) active:scale-95 whitespace-nowrap cursor-pointer">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        {{ __('messages.search') }}
                    </button>
                </div>

                <div class="flex items-center justify-center gap-6 mt-6">
                    <div class="flex items-center gap-1.5 text-xs text-(--text-muted)">
                        <x-heroicon-o-check-circle class="w-3.5 h-3.5" />
                        {{ __('messages.hero-down-sub') }}
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-(--text-muted)">
                        <x-heroicon-o-check-circle class="w-3.5 h-3.5" />
                        {{ __('messages.hero-down-sub-2') }}
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-(--text-muted)">
                        <x-heroicon-o-check-circle class="w-3.5 h-3.5" />
                        {{ __('messages.hero-down-sub-3') }}
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="w-full py-16 md:py-24 bg-(--background-2)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="mb-14">
                <span
                    class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.benefits') }}</span>
                <h2 class="text-3xl md:text-5xl font-black text-(--text-primary) mt-4 mb-4">
                    {{ __('messages.benefits-title') }}</h2>
                <p class="text-(--text-muted) text-base max-w-2xl">{{ __('messages.benefits-desc') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach ([['icon' => 'heroicon-o-square-3-stack-3d', 'title' => __('messages.benefit-1-title'), 'desc' => __('messages.benefit-1-desc')], ['icon' => 'heroicon-o-shield-check', 'title' => __('messages.benefit-2-title'), 'desc' => __('messages.benefit-2-desc')], ['icon' => 'heroicon-o-calendar', 'title' => __('messages.benefit-3-title'), 'desc' => __('messages.benefit-3-desc')]] as $benefit)
                    <div class="flex flex-col group">
                        <div
                            class="w-full h-px mb-6 bg-(--button) opacity-30 group-hover:opacity-100 transition-all duration-300">
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="p-2 rounded-sm shrink-0 bg-(--background-3)">
                                <x-dynamic-component :component="$benefit['icon']" class="w-4 h-4 text-(--text-muted)" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-(--text-primary) mb-2">{{ $benefit['title'] }}</h3>
                                <p class="text-sm text-(--text-muted) leading-relaxed">{{ $benefit['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="w-full py-16 md:py-24 bg-(--background)">
        <div class="max-w-4xl mx-auto px-6 text-center">

            <span
                class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.how-it-works') }}</span>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-14">
                @foreach ([['step' => '01', 'title' => __('messages.step-1-title'), 'desc' => __('messages.step-1-desc')], ['step' => '02', 'title' => __('messages.step-2-title'), 'desc' => __('messages.step-2-desc')], ['step' => '03', 'title' => __('messages.step-3-title'), 'desc' => __('messages.step-3-desc')]] as $step)
                    <div
                        class="flex flex-col items-center gap-3 p-8 rounded-sm bg-(--background-2) border border-(--background-3)">
                        <span class="text-5xl font-black text-(--button) opacity-50">{{ $step['step'] }}</span>
                        <h3 class="text-base font-bold text-(--text-primary)">{{ $step['title'] }}</h3>
                        <p class="text-sm text-(--text-muted) leading-relaxed max-w-xs">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <section class="w-full py-16 bg-(--background-2)">
        <div class="max-w-6xl mx-auto px-6">
            <div
                class="rounded-sm overflow-hidden grid lg:grid-cols-2 items-center bg-(--background) border border-(--background-3)">

                <div class="px-8 py-12 lg:px-14 lg:py-20">
                    <span class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-4 block">For
                        owners</span>
                    <h2 class="text-2xl md:text-4xl font-black text-(--text-primary) leading-tight mb-4">
                        {{ __('messages.cta-title') }}
                        <span class="text-(--button)">rent.use</span>
                    </h2>
                    <p class="text-(--text-muted) text-sm mb-8 max-w-md">{{ __('messages.cta-desc') }}</p>

                    @guest
                        <a href="/login"
                            class="inline-block bg-(--button) text-(--button-text) px-6 py-2.5 text-sm font-semibold rounded-sm
                                   hover:bg-(--button-h) transition-all active:scale-95">
                            {{ __('messages.get-started') }}
                        </a>
                    @endguest
                    @auth
                        <a href="{{ route('listings.create') }}"
                            class="inline-block border border-(--background-3) text-(--text-primary) px-6 py-2.5 text-sm font-medium rounded-sm
                                   hover:bg-(--background-2) transition-all">
                            {{ __('messages.post-listing') }}
                        </a>
                    @endauth
                </div>

                <div class="px-8 pb-12 lg:p-8 flex justify-center">
                    <img src="{{ asset('storage/images/about.png') }}" alt="rent.use"
                        class="w-full max-w-sm object-contain opacity-90">
                </div>

            </div>
        </div>
    </section>

    <section class="w-full py-14 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-8">
                {{ __('messages.reviews-title') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([['name' => 'Anna K.', 'text' => __('messages.review-1'), 'rating' => 5], ['name' => 'Maxim R.', 'text' => __('messages.review-2'), 'rating' => 5], ['name' => 'Laura M.', 'text' => __('messages.review-3'), 'rating' => 4]] as $review)
                    <div
                        class="p-6 rounded-sm flex flex-col gap-4 transition-all bg-(--background-2) border border-(--background-3)">
                        <div class="flex gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="text-sm {{ $i <= $review['rating'] ? 'text-(--button)' : 'text-(--background-3)' }}">★</span>
                            @endfor
                        </div>
                        <p class="text-sm text-(--text-muted) leading-relaxed">"{{ $review['text'] }}"</p>
                        <p class="text-xs font-semibold text-(--text-primary)">— {{ $review['name'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        VANTA.DOTS({
            el: "#vanta-hero",
            mouseControls: true,
            touchControls: true,
            gyroControls: false,
            minHeight: 200.00,
            minWidth: 200.00,
            scale: 0.00,
            scaleMobile: 1.00,
            color: 0xdcd1c7,
            color2: 0xffffff,
            backgroundColor: 0x121212,
            spacing: 100.00,
            showLines: false
        })
    </script>
@endpush
