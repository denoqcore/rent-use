@extends('layouts.layout')

@section('content')
    <section class="min-h-[calc(20vh-4rem)] w-full flex items-center py-20" style="background-color: var(--background);">
        <div class="w-full max-w-4xl mx-auto px-6">

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
                <div class="rounded-sm p-1.5 flex flex-col md:flex-row gap-2" style="background-color: var(--background-2)">

                    <input type="text" name="q" placeholder="Camera, car, guitar..."
                        class="flex-1 px-4 py-2.5 text-sm rounded-sm bg-transparent text-(--text-primary)
                               placeholder:text-(--text-muted) focus:outline-none transition-all">
                    <button type="submit"
                        class=" flex items-center justify-center gap-2 px-6 py-2.5 text-sm bg-(--button) text-(--button-text) font-semibold rounded-sm
                               transition-all hover:bg-(--button-h) active:scale-95 whitespace-nowrap cursor-pointer">
                        <div>
                            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        </div>{{ __('messages.search') }}
                    </button>
                </div>
            </div>

        </div>
    </section>
@endsection
