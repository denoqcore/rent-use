@extends('layouts.layout')

@section('title', 'rent.use | ' . $profileUser->name)

@section('content')

    <section class="min-h-[calc(100vh-72px)] w-full pt-44 pb-16 bg-(--background)">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-24 flex flex-col gap-3">
                    <div
                        class="rounded-sm border border-(--background-3) bg-(--background-2) p-6 flex flex-col items-center text-center gap-3">
                        <div
                            class="w-20 h-20 rounded-sm bg-(--background-3) flex items-center justify-center overflow-hidden">
                            @if ($profileUser->avatar)
                                <img src="{{ asset('storage/' . $profileUser->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl font-black text-(--text-primary)">
                                    {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <p class="text-base font-black text-(--text-primary)">{{ $profileUser->name }}</p>
                            <p class="text-xs text-(--text-muted) mt-1">
                                {{ __('messages.prof-member-since') }} {{ $profileUser->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>
                    @auth
                        @if ($profileUser->phone && auth()->id() !== $profileUser->id)
                            <button x-data="{ shown: false }" @click="shown = true"
                                class="w-full py-2.5 text-sm font-semibold rounded-sm transition-colors cursor-pointer"
                                style="background: var(--button); color: var(--button-text)"
                                x-text="shown ? '{{ $profileUser->phone }}' : '{{ __('messages.prof-phone') }}'">
                            </button>
                        @endif
                    @endauth
                    @auth
                        @if (auth()->id() !== $profileUser->id && $listings->isNotEmpty())
                            <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-4 flex flex-col gap-2">
                                <textarea id="contactMessage" rows="3" placeholder="Write a message..."
                                    class="w-full resize-none rounded-sm px-3 py-2 text-sm outline-none transition-colors bg-(--background) border border-(--background-3) text-(--text-primary)"
                                    onfocus="this.style.borderColor='var(--button)'" onblur="this.style.borderColor='var(--background-3)'"></textarea>

                                <button @click="sendFirstMessage({{ $listings->first()->id }})" id="contactSendBtn"
                                    class="w-full py-2 text-sm font-semibold rounded-sm transition-colors cursor-pointer"
                                    style="background: var(--button); color: var(--button-text)"
                                    onmouseover="this.style.background='var(--button-h)'"
                                    onmouseout="this.style.background='var(--button)'">
                                    Send message
                                </button>
                                <p id="contactSuccess" class="hidden text-xs text-center text-green-500">✓ Message sent</p>
                                <p id="contactError" class="hidden text-xs text-center text-red-400"></p>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="block text-center w-full py-2.5 text-sm font-semibold rounded-sm transition-colors"
                            style="background: var(--button); color: var(--button-text)">
                            Login to contact
                        </a>
                    @endauth

                </aside>
                <div class="flex-1 min-w-0 flex flex-col gap-6">
                    <div class="rounded-sm border border-(--background-3) bg-(--background-2) p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xs font-semibold text-(--text-muted) uppercase tracking-widest">
                                Listings
                                <span class="ml-1.5 text-(--text-primary)">{{ $activeListingsCount }}</span>
                            </h2>
                            @if ($listings->isNotEmpty())
                                <div class="flex items-center gap-2" x-data="{ search: '', category: '' }">
                                    <div class="relative">
                                        <x-heroicon-o-magnifying-glass
                                            class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-(--text-muted)" />
                                        <input type="text" x-model="search" placeholder="Search..."
                                            class="pl-8 pr-3 py-1.5 text-xs rounded-sm border border-(--background-3) bg-(--background) text-(--text-primary) outline-none focus:border-(--text-muted) transition-colors w-36">
                                    </div>
                                </div>
                            @endif
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
                                        <div class="relative w-full aspect-[4/3] bg-(--background-3) overflow-hidden">
                                            @if ($listing->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <x-heroicon-o-photo class="w-6 h-6 text-(--text-muted) opacity-40" />
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
                                                        style="background: var(--background)"
                                                        onclick="event.stopPropagation(); event.preventDefault(); this.closest('form').submit()">
                                                        @if ($isFav)
                                                            <x-heroicon-s-heart class="w-4 h-4 text-red-500" />
                                                        @else
                                                            <x-heroicon-o-heart class="w-4 h-4 text-(--text-muted)" />
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
                                                            <span class="text-sm font-semibold text-(--text-primary)">
                                                                {{ $review->reviewer->name }}
                                                            </span>
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
