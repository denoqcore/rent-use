@extends('layouts.layout')

@section('title', 'rent.use | Home')

@section('content')

    <section id="vanta-hero" class="min-h-[calc(60vh-72px)] w-full flex items-center relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none z-10"></div>
        <div class="relative z-20 w-full max-w-2xl mx-auto px-6 flex flex-col items-center text-center">

            <div
                class="absolute -inset-10 bg-radial from-white/60 via-white/30 to-transparent -z-10 blur-3xl pointer-events-none dark:hidden">
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium mb-8 text-(--text-muted) select-none">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Flag_of_Moldova.svg/1280px-Flag_of_Moldova.svg.png"
                    alt="moldova" class="h-3">
                {{ __('messages.hero-sub-2') }}
            </div>

            <h1
                class="text-5xl md:text-6xl xl:text-7xl font-black text-(--text-primary) leading-none mb-5 tracking-tight drop-shadow-sm select-none">
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

            <p class="text-(--text-muted) text-sm mb-8 max-w-xs leading-relaxed font-black select-none">
                {{ __('messages.hero-sub') }}
            </p>

            <form method="GET" action="{{ route('search') }}" class="w-full max-w-md">
                <div
                    class="flex gap-2 p-1.5 rounded-xl bg-(--background-2)/90 backdrop-blur-lg border border-(--background-3) shadow-xl shadow-blue-500/5">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Camera, car, guitar..."
                        class="flex-1 px-3 py-2.5 text-sm bg-transparent text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none">
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 text-sm font-bold bg-(--button) text-(--button-text) rounded-lg hover:bg-(--button-h) active:scale-95 transition-all cursor-pointer whitespace-nowrap shadow-md shadow-(--button)/20">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        {{ __('messages.search') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="w-full py-12 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
                @foreach ($categories as $category)
                    <a href="{{ route('search', ['category' => $category->slug]) }}"
                        class="group relative flex flex-col justify-between gap-6 p-4 rounded-sm border border-(--background-3) bg-(--background-2) transition-all duration-200 overflow-hidden">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @forelse ($listings as $listing)
                    <a href="{{ route('listings.show', $listing->slug) }}"
                        class="group rounded-sm border border-(--background-3) bg-(--background-2) overflow-hidden hover:border-(--text-muted) transition-colors duration-200">

                        <div class="h-44 bg-(--background-3)">
                            @if ($listing->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                    alt="{{ $listing->title }}"
                                    class="w-full h-full object-cover transition-transform duration-300 ease-in-out cursor-pointer">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-heroicon-o-photo class="w-7 h-7 text-(--text-primary)" />
                                </div>
                            @endif
                        </div>

                        <div class="p-3 flex flex-col gap-1">

                            <div
                                class="flex items-center gap-1 text-[10px] text-(--text-muted) tracking-wide font-medium truncate">
                                <span>{{ $listing->category->parent->name ?? '' }}</span>
                                @if ($listing->category->parent)
                                    <span class="opacity-40">/</span>
                                @endif
                                <span>{{ $listing->category->name }}</span>
                            </div>

                            <h3 class="text-sm font-bold text-(--text-primary) truncate">{{ $listing->title }}</h3>

                            <div class="flex items-center justify-between pt-2 mt-auto border-t border-(--background-3)">
                                <div class="flex items-center gap-1 text-(--text-muted)">
                                    <x-heroicon-s-map-pin class="w-3 h-3 shrink-0" />
                                    <span class="text-[11px] font-medium truncate">{{ $listing->city }}</span>
                                </div>

                                <span class="text-xs font-bold text-(--button) whitespace-nowrap ml-2">
                                    @if ($listing->price_per_day)
                                        {{ number_format($listing->price_per_day, 0, '.', ' ') }}
                                        {{ $listing->currency }}<span class="text-(--text-muted) font-normal">/day</span>
                                    @elseif ($listing->price_per_hour)
                                        {{ number_format($listing->price_per_hour, 0, '.', ' ') }}
                                        {{ $listing->currency }}<span class="text-(--text-muted) font-normal">/hr</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div>
                        <p>Empty</p>
                    </div>
                @endforelse
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
        let vantaEffect = null;

        function initVanta() {
            const isDark = document.documentElement.classList.contains('dark');

            if (vantaEffect) vantaEffect.destroy();

            vantaEffect = VANTA.DOTS({
                el: "#vanta-hero",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: isDark ? 0xb1b1b1 : 0x6550ff,
                color2: 0x828282,
                backgroundColor: isDark ? 0x222222 : 0xffffff,
                size: 3.50,
                showLines: false
            });
        }

        initVanta();

        const observer = new MutationObserver(() => initVanta());
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    </script>
@endpush
