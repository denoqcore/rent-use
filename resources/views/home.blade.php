@extends('layouts.layout')

@section('title', 'rent.use | Home')

@section('content')
    <section id="vanta-hero" class="min-h-[calc(60vh-72px)] w-full flex items-center relative overflow-hidden">

        <div class="relative z-20 w-full max-w-3xl mx-auto px-6 py-24 flex flex-col items-center text-center">

            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-sm text-xs font-medium mb-10 bg-(--background-2) text-(--text-muted) border border-(--background-3)">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/1280px-Flag_of_Moldova.svg.png"
                    alt="moldova" class="h-3.5 rounded-sm">
                {{ __('messages.hero-sub-2') }}
            </div>

            <h1
                class="text-4xl md:text-6xl xl:text-7xl font-black text-(--text-primary) leading-none mb-6 tracking-tight select-none">
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
                }, 300)" class="text-(--button)">
                    <span x-text="displayed"></span><span x-show="!done" class="animate-pulse text-(--text-muted)">|</span>
                </span>
            </h1>

            <p class="text-(--text-muted) text-base md:text-lg mb-10 max-w-sm leading-relaxed">
                {{ __('messages.hero-sub') }}
            </p>

            <div class="w-full max-w-lg">
                <div class="rounded-sm p-1.5 flex gap-2 bg-(--background-2) border border-(--background-3)">
                    <input type="text" name="q" placeholder="Camera, car, guitar..."
                        class="flex-1 px-4 py-3 text-sm rounded-sm bg-transparent text-(--text-primary)
                               placeholder:text-(--text-muted) focus:outline-none">
                    <button type="submit"
                        class="flex items-center justify-center gap-2 px-6 py-3 text-sm bg-(--button) text-(--button-text) font-bold rounded-sm
                               hover:bg-(--button-h) active:scale-95 transition-all whitespace-nowrap cursor-pointer">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        {{ __('messages.search') }}
                    </button>
                </div>
                {{-- <div class="flex items-center justify-center gap-8 mt-5">
                    @foreach (['hero-down-sub', 'hero-down-sub-2', 'hero-down-sub-3'] as $key)
                        <div class="flex items-center gap-1.5 text-xs text-(--text-muted)">
                            <x-heroicon-o-check-circle class="w-3.5 h-3.5 text-(--button)" />
                            {{ __('messages.' . $key) }}
                        </div>
                    @endforeach
                </div> --}}
            </div>

        </div>
    </section>

    <section class="w-full pb-8 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex items-center justify-between mb-6">
                <span class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                    {{ __('messages.categories') }}
                </span>
                <a href="/search"
                    class="text-xs text-(--text-muted) hover:text-(--text-primary) transition-all flex items-center gap-1">
                    {{ __('messages.more') }}
                    <x-heroicon-o-arrow-right class="w-3 h-3" />
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                @foreach ($categories as $category)
                    <a href="/"
                        class="group relative flex flex-col justify-between gap-8 p-4 rounded-sm bg-(--background-2)
                               hover:border-(--button)/50 transition-all duration-300 overflow-hidden">

                        <div
                            class="absolute top-0 left-0 w-full h-px bg-(--button) opacity-0 group-hover:opacity-100 transition-all duration-300">
                        </div>

                        <x-dynamic-component :component="$category->icon ?? 'heroicon-o-squares-2x2'"
                            class="w-5 h-5 text-(--text-muted) group-hover:text-(--button) transition-all duration-300" />

                        <div>
                            <p class="text-sm font-bold text-(--text-primary) leading-snug">{{ $category->name }}</p>
                            <p class="text-xs text-(--text-muted) mt-1">{{ $category->children->count() }}
                                {{ __('messages.subcategories') }}</p>
                        </div>

                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="w-full py-20 bg-(--background-2)">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span
                        class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.benefits') }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-(--text-primary) mt-4 mb-4 leading-tight">
                        {{ __('messages.benefits-title') }}
                    </h2>
                    <p class="text-(--text-muted) text-base leading-relaxed">{{ __('messages.benefits-desc') }}</p>
                </div>

                <div
                    class="flex flex-col gap-px bg-(--background-3) rounded-sm overflow-hidden border border-(--background-3)">
                    @foreach ([['icon' => 'heroicon-o-square-3-stack-3d', 'title' => __('messages.benefit-1-title'), 'desc' => __('messages.benefit-1-desc')], ['icon' => 'heroicon-o-shield-check', 'title' => __('messages.benefit-2-title'), 'desc' => __('messages.benefit-2-desc')], ['icon' => 'heroicon-o-calendar', 'title' => __('messages.benefit-3-title'), 'desc' => __('messages.benefit-3-desc')]] as $i => $benefit)
                        <div
                            class="group flex items-start gap-4 p-6 bg-(--background-2) hover:bg-(--background) transition-all duration-200 cursor-default">
                            <div
                                class="p-2 rounded-sm shrink-0 bg-(--background-3) group-hover:bg-(--button)/10 transition-all">
                                <x-dynamic-component :component="$benefit['icon']"
                                    class="w-4 h-4 text-(--text-muted) group-hover:text-(--button) transition-all" />
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-(--text-primary) mb-1">{{ $benefit['title'] }}</h3>
                                <p class="text-sm text-(--text-muted) leading-relaxed">{{ $benefit['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="w-full py-20 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-14">
                <div>
                    <span
                        class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.how-it-works') }}</span>
                    <h2 class="text-3xl md:text-4xl font-black text-(--text-primary) mt-3">3 simple steps</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([['step' => '01', 'title' => __('messages.step-1-title'), 'desc' => __('messages.step-1-desc'), 'icon' => 'heroicon-o-magnifying-glass'], ['step' => '02', 'title' => __('messages.step-2-title'), 'desc' => __('messages.step-2-desc'), 'icon' => 'heroicon-o-chat-bubble-left-ellipsis'], ['step' => '03', 'title' => __('messages.step-3-title'), 'desc' => __('messages.step-3-desc'), 'icon' => 'heroicon-o-arrow-path']] as $step)
                    <div
                        class="relative flex flex-col gap-6 p-6 rounded-sm bg-(--background-2) border border-(--background-3) overflow-hidden">
                        <span
                            class="absolute top-4 right-4 text-6xl font-black text-(--background-3) leading-none select-none">{{ $step['step'] }}</span>
                        <div class="p-2.5 rounded-sm bg-(--background-3) w-fit">
                            <x-dynamic-component :component="$step['icon']" class="w-5 h-5 text-(--button)" />
                        </div>
                        <div>
                            <h3 class="text-base font-black text-(--text-primary) mb-2">{{ $step['title'] }}</h3>
                            <p class="text-sm text-(--text-muted) leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
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
                    <span class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-4 block">
                        {{ __('messages.for-owners') }}
                    </span>
                    <h2 class="text-3xl md:text-4xl font-black text-(--text-primary) leading-tight mb-4">
                        {{ __('messages.cta-title') }}
                        <span class="text-(--button)">rent.use</span>
                    </h2>
                    <p class="text-(--text-muted) text-sm mb-8 max-w-md leading-relaxed">{{ __('messages.cta-desc') }}</p>

                    @guest
                        <a href="/login"
                            class="inline-flex items-center gap-2 bg-(--button) text-(--button-text) px-6 py-3 text-sm font-bold rounded-sm
                                   hover:bg-(--button-h) transition-all active:scale-95">
                            {{ __('messages.get-started') }}
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    @endguest
                    @auth
                        <a href="/"
                            class="inline-flex items-center gap-2 border border-(--background-3) text-(--text-primary) px-6 py-3 text-sm font-medium rounded-sm
                                   hover:bg-(--background-2) transition-all">
                            {{ __('messages.post-listing') }}
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    @endauth
                </div>

                <div class="px-8 pb-12 lg:p-8 flex justify-center items-center">
                    <img src="{{ asset('storage/images/about.png') }}" alt="rent.use"
                        class="w-full max-w-sm object-contain opacity-90 drop-shadow-2xl">
                </div>

            </div>
        </div>
    </section>

    <section class="w-full py-16 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex items-end justify-between mb-10">
                <div>
                    <span
                        class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.reviews-title') }}</span>
                    <div class="flex items-center gap-2 mt-3">
                        <div class="flex gap-0.5">
                            @for ($i = 0; $i < 5; $i++)
                                <span class="text-sm text-(--button)">★</span>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-(--text-primary)">4.9</span>
                        <span class="text-xs text-(--text-muted)">/ 5.0</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([['name' => 'Anna K.', 'text' => __('messages.review-1'), 'rating' => 5], ['name' => 'Maxim R.', 'text' => __('messages.review-2'), 'rating' => 5], ['name' => 'Laura M.', 'text' => __('messages.review-3'), 'rating' => 4]] as $review)
                    <div
                        class="group p-6 rounded-sm flex flex-col gap-4 bg-(--background-2) border border-(--background-3) hover:border-(--background-3) hover:bg-(--background-3)/30 transition-all">
                        <div class="flex gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="text-sm {{ $i <= $review['rating'] ? 'text-(--button)' : 'text-(--background-3)' }}">★</span>
                            @endfor
                        </div>
                        <p class="text-sm text-(--text-muted) leading-relaxed flex-1">"{{ $review['text'] }}"</p>
                        <div class="flex items-center gap-2 pt-2 border-t border-(--background-3)">
                            <div
                                class="w-6 h-6 rounded-full bg-(--background-3) flex items-center justify-center text-xs font-bold text-(--text-primary)">
                                {{ substr($review['name'], 0, 1) }}
                            </div>
                            <p class="text-xs font-semibold text-(--text-primary)">{{ $review['name'] }}</p>
                        </div>
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


{{-- <script>
    new Swiper('.listings-swiper', {
        slidesPerView: 1.2,
        spaceBetween: 12,
        navigation: {
            nextEl: '.swiper-next-listings',
            prevEl: '.swiper-prev-listings',
        },
        breakpoints: {
            640: {
                slidesPerView: 2.2,
                spaceBetween: 12
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 12
            },
        },
    });
</script> --}}
