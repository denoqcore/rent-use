@extends('layouts.layout')

@section('title', 'rent.use | ' . $profileUser->name)

@section('content')

    <section class="min-h-[calc(100vh-72px)] w-full pt-16 lg:pt-38 pb-16 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <aside class="w-full lg:w-82 shrink-0 lg:sticky lg:top-24">
                    <div class="rounded-sm border border-(--background-3) overflow-hidden">
                        <div class="relative p-6 flex flex-col items-center text-center">
                            <div
                                class="relative w-24 h-24 rounded-sm overflow-hidden bg-(--background-3) flex items-center justify-center">
                                @if ($profileUser->avatar)
                                    <img src="{{ asset('storage/' . $profileUser->avatar) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl font-black text-(--text-primary)">
                                        {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>

                            <h2 class="mt-4 text-lg font-black text-(--text-primary)">
                                {{ $profileUser->name }}
                            </h2>

                            <p class="text-xs text-(--text-muted) mt-1">
                                {{ __('messages.prof-member-since') }}
                                {{ $profileUser->created_at->format('M Y') }}
                            </p>

                            <div class="absolute top-2.5 right-2.5">
                                @if ($profileUser->plan === 'premium')
                                    <span
                                        class="px-3 py-1 rounded-sm text-[10px] font-bold bg-yellow-400/10 text-yellow-400 border border-yellow-400/20">
                                        PREMIUM
                                    </span>
                                @elseif($profileUser->plan === 'pro')
                                    <span
                                        class="px-3 py-1 rounded-sm text-[10px] font-bold bg-blue-400/10 text-blue-400 border border-blue-400/20">
                                        PRO
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-sm text-[10px] font-bold bg-(--background-3) text-(--text-muted) border border-(--background-3)">
                                        STARTER
                                    </span>
                                @endif
                            </div>

                        </div>

                        <div class="p-4 flex flex-col items-center gap-3">
                            @auth
                                @if (auth()->id() !== $profileUser->id)
                                    <div class="w-full flex gap-2">

                                        @if ($profileUser->phone)
                                            <button x-data="{ shown: false }" @click="shown = !shown"
                                                :class="shown ? 'bg-(--background-2)' : 'bg-(--background)'"
                                                class="flex-1 flex items-center justify-center gap-1.5 py-3 rounded-sm text-sm font-semibold transition-colors cursor-pointer border border-(--background-3)"
                                                style="color: var(--text-primary)">
                                                <x-heroicon-o-phone class="w-4 h-4" />
                                                <span
                                                    x-text="shown ? '{{ formatPhone($profileUser->phone) }}' : '{{ __('messages.prof-phone') }}'"></span>
                                            </button>
                                        @endif

                                        @if ($listings->isNotEmpty())
                                            <button x-data @click="$dispatch('toggle-contact-form')"
                                                class="flex-1 flex items-center justify-center gap-1.5 py-3 rounded-sm text-sm font-semibold transition-colors cursor-pointer lg:flex hidden"
                                                style="background: var(--button); color: var(--button-text)"
                                                onmouseover="this.style.background='var(--button-h)'"
                                                onmouseout="this.style.background='var(--button)'">
                                                <x-heroicon-o-envelope class="w-4 h-4" />
                                                {{ __('messages.send-message') }}
                                            </button>

                                            <button @click="$dispatch('open-message-sheet')"
                                                class="flex-1 flex items-center justify-center gap-1.5 py-3 rounded-sm text-sm font-semibold transition-colors cursor-pointer lg:hidden"
                                                style="background: var(--button); color: var(--button-text)"
                                                onmouseover="this.style.background='var(--button-h)'"
                                                onmouseout="this.style.background='var(--button)'">
                                                <x-heroicon-o-envelope class="w-4 h-4" />
                                                {{ __('messages.send-message') }}
                                            </button>
                                        @endif

                                    </div>
                                    @if ($listings->isNotEmpty())
                                        <div x-data="{ open: false }" @toggle-contact-form.window="open = !open" x-show="open"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-1" class="w-full hidden lg:block"
                                            style="display: none">
                                            <textarea id="contactMessage" rows="3" placeholder="{{ __('messages.write-message') }}"
                                                class="w-full resize-none rounded-sm px-3 py-2.5 text-sm outline-none transition-colors"
                                                style="border: 1px solid var(--background-3); color: var(--text-primary); background: var(--background)"
                                                onfocus="this.style.borderColor='var(--button)'" onblur="this.style.borderColor='var(--background-3)'"></textarea>

                                            <button @click="sendFirstMessage({{ $listings->first()->id }})" id="contactSendBtn"
                                                class="w-full mt-2 py-2.5 rounded-sm text-sm font-semibold transition-colors cursor-pointer"
                                                style="background: var(--button); color: var(--button-text)"
                                                onmouseover="this.style.background='var(--button-h)'"
                                                onmouseout="this.style.background='var(--button)'">
                                                {{ __('messages.send-message') }}
                                            </button>

                                            <p id="contactSuccess" class="hidden text-xs text-center text-green-500 mt-2">
                                                ✓ {{ __('messages.message-sent') }}
                                            </p>
                                            <p id="contactError" class="hidden text-xs text-center text-red-400 mt-1"></p>
                                        </div>
                                    @endif

                                    @if ($listings->isNotEmpty())
                                        <div x-data="{ open: false }" @open-message-sheet.window="open = true" x-show="open"
                                            x-cloak class="fixed inset-0 z-50 flex flex-col justify-end lg:hidden">
                                            <div @click="open = false" x-show="open"
                                                x-transition:enter="transition-opacity duration-200"
                                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition-opacity duration-200"
                                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                                class="absolute inset-0 bg-black/40"></div>

                                            <div x-show="open" x-transition:enter="transition-transform duration-300 ease-out"
                                                x-transition:enter-start="translate-y-full"
                                                x-transition:enter-end="translate-y-0"
                                                x-transition:leave="transition-transform duration-200 ease-in"
                                                x-transition:leave-start="translate-y-0"
                                                x-transition:leave-end="translate-y-full"
                                                class="relative rounded-t-2xl p-4 pb-8 flex flex-col gap-3"
                                                style="background: var(--background)">

                                                <div class="flex justify-center pb-1">
                                                    <div class="w-9 h-1 rounded-full" style="background: var(--background-3)">
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-semibold" style="color: var(--text-primary)">
                                                        {{ __('messages.message') }} {{ $profileUser->name }}
                                                    </p>
                                                    <button @click="open = false" class="cursor-pointer"
                                                        style="color: var(--text-secondary)">
                                                        <x-heroicon-o-x-mark class="w-5 h-5" />
                                                    </button>
                                                </div>

                                                <div class="rounded-sm px-3 py-2 text-xs"
                                                    style="background: var(--background-2); color: var(--text-secondary)">
                                                    <span
                                                        style="color: var(--text-primary); font-weight: 500;">{{ $listings->first()->title }}</span>
                                                    ·
                                                    {{ $listings->first()->price_per_day
                                                        ? '$' . $listings->first()->price_per_day . '/' . strtolower(__('messages.day'))
                                                        : '$' . $listings->first()->price_per_hour . '/' . strtolower(__('messages.hour')) }}
                                                </div>

                                                <textarea id="contactMessageSheet" rows="4" placeholder="{{ __('messages.write-message') }}"
                                                    class="w-full resize-none rounded-sm px-3 py-3 text-sm outline-none"
                                                    style="background: var(--background); border: 1px solid var(--background-3); color: var(--text-primary);"></textarea>

                                                <button @click="sendFirstMessage({{ $listings->first()->id }}, 'sheet')"
                                                    id="contactSendBtnSheet"
                                                    class="w-full py-3 rounded-sm text-sm font-semibold transition-colors cursor-pointer"
                                                    style="background: var(--button); color: var(--button-text)">
                                                    {{ __('messages.send-message') }}
                                                </button>

                                                <p id="contactSuccessSheet" class="hidden text-xs text-center text-green-500">
                                                    ✓ {{ __('messages.message-sent') }}
                                                </p>
                                                <p id="contactErrorSheet" class="hidden text-xs text-center text-red-400"></p>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="block text-center w-full py-3 rounded-sm text-sm font-semibold"
                                    style="background: var(--button); color: var(--button-text)">
                                    {{ __('messages.login-to-contact') }}
                                </a>
                            @endauth
                        </div>

                    </div>

                </aside>

                <div class="flex-1 min-w-0 flex flex-col gap-6">
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                Listings
                                <span class="ml-1.5 text-(--text-primary)">{{ $activeListingsCount }}</span>
                            </h2>
                        </div>

                        @if ($listings->isEmpty())
                            <div class="flex flex-col items-center justify-center py-16 gap-3">
                                <x-heroicon-o-squares-2x2 class="w-8 h-8 text-(--background-3)" />
                                <p class="text-sm text-(--text-muted)">{{ __('messages.prof-nolisting') }}</p>
                            </div>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach ($listings as $listing)
                                    <a href="{{ route('listings.show', $listing->slug) }}"
                                        class="group flex flex-col rounded-sm border border-(--background-3) overflow-hidden hover:border-(--text-muted)/30 transition-colors bg-(--background)">
                                        <div class="relative w-full aspect-4/3 bg-(--background-3) overflow-hidden">
                                            @if ($listing->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <x-heroicon-o-photo class="w-6 h-6 text-(--text-muted) opacity-40" />
                                                </div>
                                            @endif

                                            @if (
                                                $listing->is_boosted &&
                                                    $listing->boosted_until?->isFuture() &&
                                                    in_array($listing->user->plan, ['pro', 'premium']) &&
                                                    $listing->user->isActivePlan())
                                                <div class="absolute top-3.75 left-3">
                                                    <span
                                                        class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold rounded-sm bg-(--bg-star) text-(--star-color)">
                                                        <x-heroicon-s-star class="w-4 h-4" />
                                                    </span>
                                                </div>
                                            @endif

                                            @auth
                                                @php $isFav = auth()->user()->favoriteListings->contains($listing->id); @endphp
                                                <form method="POST"
                                                    action="{{ $isFav ? route('favorites.destroy', $listing) : route('favorites.store', $listing) }}"
                                                    class="absolute top-2 right-2" @click.prevent="$el.submit()">
                                                    @csrf
                                                    @if ($isFav)
                                                        @method('DELETE')
                                                    @endif
                                                    <button type="submit"
                                                        class="w-7 h-7 rounded-sm flex items-center justify-center transition-colors cursor-pointer"
                                                        onclick="event.stopPropagation(); event.preventDefault(); this.closest('form').submit()">
                                                        @if ($isFav)
                                                            <x-heroicon-s-heart class="w-6 h-6 text-red-500" />
                                                        @else
                                                            <x-heroicon-o-heart class="w-6 h-6 text-white" />
                                                        @endif
                                                    </button>
                                                </form>
                                            @endauth
                                        </div>
                                        <div class="p-3 flex flex-col gap-1">
                                            <p
                                                class="text-sm font-semibold text-(--text-primary) line-clamp-2 group-hover:underline leading-snug">
                                                {{ $listing->title }}
                                            </p>
                                            <p class="text-xs text-(--text-muted)">{{ $listing->city->name }}</p>
                                            <p class="text-sm font-bold text-(--text-price) mt-1">
                                                @if ($listing->price_per_day)
                                                    {{ number_format($listing->price_per_day) }}
                                                    {{ $listing->currency }}<span
                                                        class="text-xs font-normal text-(--text-muted)">/day</span>
                                                @elseif ($listing->price_per_hour)
                                                    {{ number_format($listing->price_per_hour) }}
                                                    {{ $listing->currency }}<span
                                                        class="text-xs font-normal text-(--text-muted)">/hr</span>
                                                @endif
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @if ($listings->hasPages())
                                <div class="mt-6">
                                    {{ $listings->links() }}
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                {{ __('messages.prof-reviews') }}
                            </h2>
                            @auth
                                @if (auth()->id() !== $profileUser->id)
                                    <button @click="reviewModal = true; reviewRating = {{ $userReview?->rating ?? 0 }}"
                                        class="px-3 py-1.5 text-xs font-semibold rounded-sm transition-colors cursor-pointer"
                                        style="background: var(--button); color: var(--button-text)"
                                        onmouseover="this.style.background='var(--button-h)'"
                                        onmouseout="this.style.background='var(--button)'">
                                        {{ $userReview ? 'Edit Review' : 'Leave a Review' }}
                                    </button>
                                @endif
                            @endauth
                        </div>
                        @if (session('success'))
                            <div
                                class="mb-4 text-xs px-3 py-2 rounded-sm bg-green-500/10 text-green-500 border border-green-500/20">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="flex flex-col sm:flex-row gap-8">
                            <div class="flex flex-col items-start gap-1 shrink-0">
                                <p class="text-4xl font-black text-(--text-primary)">
                                    {{ number_format($avgRating, 1) }}
                                </p>
                                <div class="flex items-center gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($avgRating))
                                            <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                                        @elseif ($avgRating - floor($avgRating) >= 0.5 && $i == ceil($avgRating))
                                            <x-heroicon-s-star class="w-4 h-4 text-yellow-400 opacity-50" />
                                        @else
                                            <x-heroicon-o-star class="w-4 h-4 text-(--background-3)" />
                                        @endif
                                    @endfor
                                </div>
                                <p class="text-xs text-(--text-muted) mt-1">
                                    {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}
                                </p>
                            </div>

                            <div class="flex-1 flex flex-col gap-2">
                                @foreach ($distribution as $star => $count)
                                    @php $percent = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0 @endphp
                                    <div class="flex items-center gap-3 text-xs text-(--text-muted)">
                                        <span class="w-12 text-right shrink-0">
                                            {{ $star }} {{ $star === 1 ? 'star' : 'stars' }}
                                        </span>
                                        <div class="flex-1 h-1.5 rounded-full bg-(--background-3) overflow-hidden">
                                            <div class="h-full rounded-full bg-yellow-400 transition-all duration-500"
                                                style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="w-4 shrink-0 text-right">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-6 border-t border-(--background-3) pt-5">
                            @if ($reviews->isEmpty())
                                <div class="flex flex-col items-center justify-center py-10 gap-2">
                                    <x-heroicon-o-chat-bubble-left-right class="w-7 h-7 text-(--background-3)" />
                                    <p class="text-sm text-(--text-muted)">No reviews yet</p>
                                </div>
                            @else
                                <div class="flex flex-col gap-5">
                                    @foreach ($reviews as $review)
                                        <div class="flex gap-3">
                                            <div
                                                class="shrink-0 w-8 h-8 rounded-sm bg-(--background-3) overflow-hidden flex items-center justify-center">
                                                @if ($review->reviewer->avatar)
                                                    <img src="{{ asset('storage/' . $review->reviewer->avatar) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <span class="text-xs font-bold text-(--text-muted)">
                                                        {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <a href="{{ route('profile.public', $review->reviewer) }}"
                                                                class="text-sm font-semibold text-(--text-primary) hover:underline">
                                                                {{ $review->reviewer->name }}
                                                            </a>

                                                            <span class="text-xs text-(--text-muted)">
                                                                {{ $review->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center gap-0.5 mt-0.5">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $review->rating)
                                                                    <x-heroicon-s-star class="w-3 h-3 text-yellow-400" />
                                                                @else
                                                                    <x-heroicon-o-star
                                                                        class="w-3 h-3 text-(--background-3)" />
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>

                                                    @auth
                                                        @if (auth()->id() === $review->reviewer_id)
                                                            <form action="{{ route('reviews.destroy', $review) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-(--text-muted) hover:text-red-400 transition-colors cursor-pointer">
                                                                    <x-heroicon-o-trash class="w-3.5 h-3.5" />
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </div>

                                                @if ($review->comment)
                                                    <p
                                                        class="mt-1.5 text-sm text-(--text-secondary) leading-relaxed break-words">
                                                        {{ $review->comment }}
                                                    </p>
                                                @endif

                                                @auth
                                                    @if (auth()->id() !== $review->reviewer_id)
                                                        <div class="flex items-center gap-3 mt-2" x-data="{
                                                            likes: {{ $review->likesCount() }},
                                                            dislikes: {{ $review->dislikesCount() }},
                                                            myVote: {{ isset($myVotes[$review->id]) ? ($myVotes[$review->id] ? 'true' : 'false') : 'null' }},
                                                            async vote(isLike) {
                                                                const csrf = document.querySelector('meta[name=csrf-token]').content;
                                                                const res = await fetch('{{ route('reviews.vote', $review) }}', {
                                                                    method: 'POST',
                                                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                                                                    body: JSON.stringify({ is_like: isLike })
                                                                });
                                                                const data = await res.json();
                                                                this.likes = data.likes;
                                                                this.dislikes = data.dislikes;
                                                                if (this.myVote === isLike) {
                                                                    this.myVote = null;
                                                                } else {
                                                                    this.myVote = isLike;
                                                                }
                                                            }
                                                        }">
                                                            <button @click="vote(true)"
                                                                class="flex items-center gap-1 text-xs transition-colors cursor-pointer"
                                                                :class="myVote === true ? 'text-green-500' :
                                                                    'text-(--text-muted) hover:text-green-500'">
                                                                <x-heroicon-o-hand-thumb-up class="w-3.5 h-3.5" />
                                                                <span x-text="likes"></span>
                                                            </button>
                                                            <button @click="vote(false)"
                                                                class="flex items-center gap-1 text-xs transition-colors cursor-pointer"
                                                                :class="myVote === false ? 'text-red-400' :
                                                                    'text-(--text-muted) hover:text-red-400'">
                                                                <x-heroicon-o-hand-thumb-down class="w-3.5 h-3.5" />
                                                                <span x-text="dislikes"></span>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    @auth
                        @if (auth()->id() !== $profileUser->id)
                            <div x-show="reviewModal" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">

                                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="reviewModal = false"></div>

                                <div x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="relative z-10 w-full max-w-md rounded-sm border border-(--background-3) bg-(--background-2) p-6 shadow-2xl">

                                    <div class="flex items-center justify-between mb-5">
                                        <h3 class="text-sm font-semibold text-(--text-primary)">
                                            {{ $userReview ? 'Edit review' : 'Leave a review' }}
                                            <span class="text-(--text-muted)">for</span> {{ $profileUser->name }}
                                        </h3>
                                        <button @click="reviewModal = false"
                                            class="text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                                            <x-heroicon-o-x-mark class="w-4 h-4" />
                                        </button>
                                    </div>

                                    <form action="{{ route('reviews.store', $profileUser) }}" method="POST">
                                        @csrf
                                        <div class="mb-5">
                                            <label class="block text-xs text-(--text-muted) mb-2 uppercase tracking-widest">
                                                Rating *
                                            </label>
                                            <div class="flex items-center gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <button type="button" @click="reviewRating = {{ $i }}"
                                                        @mouseover="reviewHover = {{ $i }}"
                                                        @mouseleave="reviewHover = 0"
                                                        class="transition-transform hover:scale-110 cursor-pointer">
                                                        <x-heroicon-s-star class="w-7 h-7 transition-colors"
                                                            ::class="{{ $i }} <= (reviewHover || reviewRating) ?
                                                                'text-yellow-400' : 'text-(--background-3)'" />
                                                    </button>
                                                @endfor
                                                <input type="hidden" name="rating" :value="reviewRating">
                                                <span class="ml-2 text-xs text-(--text-muted)"
                                                    x-text="reviewLabels[reviewHover || reviewRating]"></span>
                                            </div>
                                            @error('rating')
                                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-5">
                                            <label class="block text-xs text-(--text-muted) mb-2 uppercase tracking-widest">
                                                Comment <span class="normal-case">(optional)</span>
                                            </label>
                                            <textarea name="comment" rows="4" maxlength="1000" placeholder="Share your experience..."
                                                class="w-full rounded-sm border border-(--background-3) bg-(--background) px-3 py-2 text-sm text-(--text-primary) placeholder:text-(--text-muted) focus:outline-none focus:border-(--button) transition-colors resize-none">{{ old('comment', $userReview?->comment) }}</textarea>
                                            @error('comment')
                                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex gap-3">
                                            <button type="submit"
                                                class="flex-1 py-2 text-xs font-semibold rounded-sm transition-colors cursor-pointer"
                                                style="background: var(--button); color: var(--button-text)"
                                                onmouseover="this.style.background='var(--button-h)'"
                                                onmouseout="this.style.background='var(--button)'">
                                                {{ $userReview ? 'Update Review' : 'Submit Review' }}
                                            </button>
                                            <button type="button" @click="reviewModal = false"
                                                class="px-4 py-2 text-xs font-semibold rounded-sm border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) transition-colors cursor-pointer">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth

                </div>
            </div>
        </div>
    </section>

@endsection
