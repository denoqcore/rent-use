@extends('layouts.layout')

@section('title', 'rent.use | Home')

@section('content')

    <section id="vanta-hero" class="min-h-[calc(60vh-72px)] w-full flex items-center relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none z-10"></div>
        <div class="relative z-20 w-full max-w-2xl mx-auto px-6 flex flex-col items-center text-center">

            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-sm text-xs font-medium mb-8 bg-(--background-2) text-(--text-muted) border border-(--background-3)">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/1280px-Flag_of_Moldova.svg.png"
                    alt="moldova" class="h-3">
                {{ __('messages.hero-sub-2') }}
            </div>

            <h1 class="text-5xl md:text-6xl xl:text-7xl font-black text-(--text-primary) leading-none mb-5 tracking-tight">
                {{ __('messages.rent-hero') }}<br>
                <span x-data="{
                    full: '{{ __('messages.rent-hero-2') }}',
                    displayed: '',
                    index: 0,
                    done: false
                }" x-init="setTimeout(() => {
                    let iv = setInterval(() => {
                        if (index < full.length) {
                            displayed += full[index];
                            index++;
                        } else {
                            done = true;
                            clearInterval(iv);
                        }
                    }, 60)
                }, 400)" class="text-(--button)">
                    <span x-text="displayed"></span><span x-show="!done" class="animate-pulse text-(--text-muted)">|</span>
                </span>
            </h1>

            <p class="text-(--text-muted) text-sm mb-8 max-w-xs leading-relaxed">
                {{ __('messages.hero-sub') }}
            </p>

            <div class="w-full max-w-md">
                <div class="flex gap-2 p-1.5 rounded-sm bg-(--background-2) border border-(--background-3)">
                    <input type="text" placeholder="Camera, car, guitar..."
                        class="flex-1 px-3 py-2.5 text-sm bg-transparent text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none">
                    <button
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold bg-(--button) text-(--button-text) rounded-sm hover:bg-(--button-h) active:scale-95 transition-all cursor-pointer whitespace-nowrap">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        {{ __('messages.search') }}
                    </button>
                </div>
            </div>

        </div>
    </section>

    <section class="w-full py-12 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
                @foreach ($categories as $category)
                    <a href="/"
                        class="group relative flex flex-col justify-between gap-6 p-4 rounded-sm border border-(--background-3) bg-(--background-2) hover:border-(--button)/40 transition-all duration-200 overflow-hidden">
                        <div
                            class="absolute top-0 left-0 w-full h-px bg-(--button) opacity-0 group-hover:opacity-100 transition-all duration-300">
                        </div>
                        <x-dynamic-component :component="$category->icon ?? 'heroicon-o-squares-2x2'"
                            class="w-4 h-4 text-(--text-muted) group-hover:text-(--button) transition-all duration-200" />
                        <div>
                            <p class="text-sm font-bold text-(--text-primary)">{{ $category->name }}</p>
                            <p class="text-xs text-(--text-muted) mt-0.5">{{ $category->children->count() }}
                                {{ __('messages.subcategories') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>

    <section class="w-full bg-(--background) py-12">
        <div class="max-w-6xl mx-auto px-6">

            <div class="flex items-center justify-between mb-6">
                <span class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                    {{ __('messages.latest') }}
                </span>
                <a href="/search"
                    class="flex items-center gap-1 text-xs text-(--text-muted) hover:text-(--text-primary) transition-all">
                    {{ __('messages.more') }}
                    <x-heroicon-o-arrow-right class="w-3 h-3" />
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mb-3">
                @for ($i = 0; $i < 4; $i++)
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) overflow-hidden">
                        <div class="h-40 bg-(--background-3) flex items-center justify-center">
                            <x-heroicon-o-photo class="w-6 h-6" style="color:#2a2a2a" />
                        </div>
                        <div class="p-3 flex flex-col gap-2">
                            <div class="h-3 w-2/3 rounded-sm bg-(--background-3) animate-pulse"></div>
                            <div class="h-4 w-1/3 rounded-sm bg-(--background-3) animate-pulse"></div>
                            <div class="flex items-center justify-between pt-2 border-t border-(--background-3)">
                                <div class="h-3 w-16 rounded-sm bg-(--background-3) animate-pulse"></div>
                                <div class="h-3 w-12 rounded-sm bg-(--background-3) animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @for ($i = 0; $i < 4; $i++)
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) overflow-hidden">
                        <div class="h-40 bg-(--background-3) flex items-center justify-center">
                            <x-heroicon-o-photo class="w-6 h-6" style="color:#2a2a2a" />
                        </div>
                        <div class="p-3 flex flex-col gap-2">
                            <div class="h-3 w-2/3 rounded-sm bg-(--background-3) animate-pulse"></div>
                            <div class="h-4 w-1/3 rounded-sm bg-(--background-3) animate-pulse"></div>
                            <div class="flex items-center justify-between pt-2 border-t border-(--background-3)">
                                <div class="h-3 w-16 rounded-sm bg-(--background-3) animate-pulse"></div>
                                <div class="h-3 w-12 rounded-sm bg-(--background-3) animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

        </div>
    </section>

    <section class="w-full py-20 bg-(--background-2)">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <div>
                    <span
                        class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.benefits') }}</span>
                    <h2 class="text-4xl md:text-5xl font-black text-(--text-primary) mt-3 mb-4 leading-tight">
                        {{ __('messages.benefits-title') }}
                    </h2>
                    <p class="text-(--text-muted) text-sm leading-relaxed max-w-sm">{{ __('messages.benefits-desc') }}</p>
                </div>

                <div
                    class="flex flex-col divide-y divide-(--background-3) border border-(--background-3) rounded-sm overflow-hidden">
                    @foreach ([['icon' => 'heroicon-o-square-3-stack-3d', 'title' => __('messages.benefit-1-title'), 'desc' => __('messages.benefit-1-desc')], ['icon' => 'heroicon-o-shield-check', 'title' => __('messages.benefit-2-title'), 'desc' => __('messages.benefit-2-desc')], ['icon' => 'heroicon-o-calendar', 'title' => __('messages.benefit-3-title'), 'desc' => __('messages.benefit-3-desc')]] as $benefit)
                        <div
                            class="group flex items-start gap-4 p-5 bg-(--background-2) hover:bg-(--background) transition-all duration-200">
                            <div
                                class="p-2 rounded-sm bg-(--background-3) group-hover:bg-(--button)/10 transition-all shrink-0">
                                <x-dynamic-component :component="$benefit['icon']"
                                    class="w-4 h-4 text-(--text-muted) group-hover:text-(--button) transition-all" />
                            </div>
                            <div>
                                <p class="text-sm font-bold text-(--text-primary) mb-1">{{ $benefit['title'] }}</p>
                                <p class="text-xs text-(--text-muted) leading-relaxed">{{ $benefit['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    <section class="w-full py-20 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="mb-12">
                <span
                    class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.how-it-works') }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach ([['step' => '01', 'icon' => 'heroicon-o-magnifying-glass', 'title' => __('messages.step-1-title'), 'desc' => __('messages.step-1-desc')], ['step' => '02', 'icon' => 'heroicon-o-chat-bubble-left-ellipsis', 'title' => __('messages.step-2-title'), 'desc' => __('messages.step-2-desc')], ['step' => '03', 'icon' => 'heroicon-o-arrow-path', 'title' => __('messages.step-3-title'), 'desc' => __('messages.step-3-desc')]] as $step)
                    <div class="relative p-6 rounded-sm bg-(--background-2) border border-(--background-3) overflow-hidden">
                        <span class="absolute top-3 right-4 text-5xl font-black leading-none select-none"
                            style="color:#222">{{ $step['step'] }}</span>
                        <div class="mb-6">
                            <x-dynamic-component :component="$step['icon']" class="w-5 h-5 text-(--button)" />
                        </div>
                        <p class="text-sm font-black text-(--text-primary) mb-1.5">{{ $step['title'] }}</p>
                        <p class="text-xs text-(--text-muted) leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <section class="w-full py-16 bg-(--background-2)">
        <div class="max-w-6xl mx-auto px-6">
            <div
                class="grid lg:grid-cols-2 items-center gap-0 rounded-sm overflow-hidden border border-(--background-3) bg-(--background)">

                <div class="px-10 py-14 lg:px-16 lg:py-20">
                    <span
                        class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest mb-4 block">{{ __('messages.for-owners') }}</span>
                    <h2 class="text-3xl md:text-4xl font-black text-(--text-primary) leading-tight mb-3">
                        {{ __('messages.cta-title') }} <span class="text-(--button)">rent.use</span>
                    </h2>
                    <p class="text-sm text-(--text-muted) mb-8 leading-relaxed max-w-sm">{{ __('messages.cta-desc') }}</p>
                    @guest
                        <a href="/login"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold bg-(--button) text-(--button-text) rounded-sm hover:bg-(--button-h) active:scale-95 transition-all">
                            {{ __('messages.get-started') }}
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    @endguest
                    @auth
                        <a href="/"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium border border-(--background-3) text-(--text-primary) rounded-sm hover:bg-(--background-2) transition-all">
                            {{ __('messages.post-listing') }}
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    @endauth
                </div>

                <div
                    class="flex items-center justify-center px-10 py-14 border-t lg:border-t-0 lg:border-l border-(--background-3)">
                    <img src="{{ asset('storage/images/about.png') }}" alt="rent.use"
                        class="w-full max-w-xs object-contain opacity-80">
                </div>

            </div>
        </div>
    </section>

    <section class="w-full py-16 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="mb-8">
                <span
                    class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">{{ __('messages.reviews-title') }}</span>
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex gap-0.5">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="text-xs text-(--button)">★</span>
                        @endfor
                    </div>
                    <span class="text-sm font-bold text-(--text-primary)">4.9</span>
                    <span class="text-xs text-(--text-muted)">/ 5.0</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach ([['name' => 'Anna K.', 'text' => __('messages.review-1'), 'rating' => 5], ['name' => 'Maxim R.', 'text' => __('messages.review-2'), 'rating' => 5], ['name' => 'Laura M.', 'text' => __('messages.review-3'), 'rating' => 4]] as $review)
                    <div class="flex flex-col gap-3 p-5 rounded-sm bg-(--background-2) border border-(--background-3)">
                        <div class="flex gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="text-xs {{ $i <= $review['rating'] ? 'text-(--button)' : 'text-(--background-3)' }}">★</span>
                            @endfor
                        </div>
                        <p class="text-sm text-(--text-muted) leading-relaxed flex-1">"{{ $review['text'] }}"</p>
                        <div class="flex items-center gap-2 pt-3 border-t border-(--background-3)">
                            <div
                                class="w-6 h-6 rounded-full bg-(--background-3) flex items-center justify-center text-xs font-bold text-(--text-primary)">
                                {{ substr($review['name'], 0, 1) }}</div>
                            <span class="text-xs font-semibold text-(--text-primary)">{{ $review['name'] }}</span>
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
