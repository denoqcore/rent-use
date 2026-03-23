@extends('layouts.layout')

@section('title', 'rent.use | ' . __('messages.create_listing'))

@section('content')
    <section class="min-h-[calc(100vh-72px)] w-full py-12 bg-(--background)">
        <div class="max-w-xl mx-auto px-6 flex flex-col gap-8" x-data="{
            step: 1,
            parent: '{{ old('parent_category') }}',
            categoryId: '{{ old('category_id') }}',
            currency: 'MDL',
            currencies: ['MDL', 'EUR', 'USD'],
        
            title: '{{ old('title') }}',
            city: '{{ old('city') }}',
            pricePerDay: '{{ old('price_per_day') }}',
        
            get canProceedStep1() {
                return this.parent !== '' && this.categoryId !== '';
            },
            get canProceedStep2() {
                return this.title.trim().length >= 5 &&
                    this.city.trim().length >= 2 &&
                    this.pricePerDay > 0;
            },
            nextStep() {
                if (this.step === 1 && !this.canProceedStep1) return;
                if (this.step === 2 && !this.canProceedStep2) return;
                this.step++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            prevStep() {
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }">

            <div>
                <h1 class="text-xl font-black text-(--text-primary)">{{ __('messages.create_listing') }}</h1>
                <p class="text-xs text-(--text-muted) mt-1">{{ __('messages.create_listing_sub') }}</p>
            </div>

            <div class="flex items-center gap-0">

                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                        :class="step >= 1 ?
                            'bg-(--button) text-(--button-text)' :
                            'bg-(--background-2) text-(--text-muted) border border-(--background-3)'">
                        <template x-if="step > 1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step <= 1">
                            <span>1</span>
                        </template>
                    </div>
                    <span class="text-[10px] font-medium transition-colors duration-300"
                        :class="step >= 1 ? 'text-(--text-primary)' : 'text-(--text-muted)'">
                        {{ __('messages.step_category') }}
                    </span>
                </div>

                <div class="flex-1 h-px mb-4 mx-2 transition-all duration-500"
                    :class="step >= 2 ? 'bg-(--button)' : 'bg-(--background-3)'"></div>

                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                        :class="step >= 2 ?
                            'bg-(--button) text-(--button-text)' :
                            'bg-(--background-2) text-(--text-muted) border border-(--background-3)'">
                        <template x-if="step > 2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step <= 2">
                            <span>2</span>
                        </template>
                    </div>
                    <span class="text-[10px] font-medium transition-colors duration-300"
                        :class="step >= 2 ? 'text-(--text-primary)' : 'text-(--text-muted)'">
                        {{ __('messages.step_details') }}
                    </span>
                </div>

                <div class="flex-1 h-px mb-4 mx-2 transition-all duration-500"
                    :class="step >= 3 ? 'bg-(--button)' : 'bg-(--background-3)'"></div>

                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                        :class="step >= 3 ?
                            'bg-(--button) text-(--button-text)' :
                            'bg-(--background-2) text-(--text-muted) border border-(--background-3)'">
                        <span>3</span>
                    </div>
                    <span class="text-[10px] font-medium transition-colors duration-300"
                        :class="step >= 3 ? 'text-(--text-primary)' : 'text-(--text-muted)'">
                        {{ __('messages.step_photos') }}
                    </span>
                </div>

            </div>

            @if ($errors->any())
                <div role="alert" class="p-4 rounded-sm border border-red-500 bg-red-500/10">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-500">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-6">
                @csrf

                <input type="hidden" name="currency" :value="currency">

                <div x-show="step === 1" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex flex-col gap-5">

                    <div class="flex flex-col gap-2">
                        <p class="text-sm font-semibold text-(--text-primary)">{{ __('messages.choose_main_category') }}/p>
                        <p class="text-xs text-(--text-muted)">{{ __('messages.choose_main_category_sub') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($categories as $category)
                            <button type="button" @click="parent = '{{ $category->id }}'; categoryId = ''"
                                :class="parent == '{{ $category->id }}' ?
                                    'border-(--button) bg-(--background-2) text-(--text-primary)' :
                                    'border-(--background-3) bg-(--background-2) text-(--text-muted) hover:border-(--text-muted)'"
                                class="flex items-center gap-3 px-4 py-3 rounded-sm border text-sm font-medium transition-all text-left cursor-pointer">
                                <span class="w-2 h-2 rounded-full shrink-0 transition-all"
                                    :class="parent == '{{ $category->id }}' ? 'bg-(--button)' : 'bg-(--background-3)'">
                                </span>
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>

                    <input type="hidden" name="parent_category" :value="parent">

                    @foreach ($categories as $category)
                        <div x-show="parent == '{{ $category->id }}'" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col gap-2">

                            <p class="text-sm font-semibold text-(--text-primary)">
                                {{ __('messages.choose_subcategory') }}/p>

                            <div class="flex flex-col gap-1">
                                @foreach ($category->children as $child)
                                    <button type="button" @click="categoryId = '{{ $child->id }}'"
                                        :class="categoryId == '{{ $child->id }}' ?
                                            'border-(--button) text-(--text-primary)' :
                                            'border-(--background-3) text-(--text-muted) hover:border-(--text-muted)'"
                                        class="flex items-center justify-between px-4 py-2.5 rounded-sm border bg-(--background-2) text-sm transition-all text-left cursor-pointer">
                                        <span>{{ $child->name }}</span>
                                        <svg x-show="categoryId == '{{ $child->id }}'"
                                            xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-(--button)"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <input type="hidden" name="category_id" :value="categoryId">

                    <button type="button" @click="nextStep()" :disabled="!canProceedStep1"
                        :class="canProceedStep1 ?
                            'bg-(--button) text-(--button-text) hover:bg-(--button-h) cursor-pointer' :
                            'bg-(--background-3) text-(--text-muted) cursor-not-allowed opacity-50'"
                        class="w-full py-2.5 text-sm font-bold rounded-sm transition-all active:scale-95">
                        {{ __('messages.continue') }}
                    </button>

                </div>

                <div x-show="step === 2" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex flex-col gap-4">

                    <div class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-(--background-3)/30 border border-(--background-3)"
                        x-show="categoryId !== ''" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <x-heroicon-s-tag class="w-3 h-3 text-(--text-muted)/60" />
                        <div class="flex items-center gap-1.5 text-[11px] tracking-tight">
                            <span class="text-(--text-muted) font-medium"
                                x-text="
                                    @foreach ($categories as $category)
                                        parent == '{{ $category->id }}' ? '{{ $category->name }}' : @endforeach ''
                                "></span>
                            <span class="text-(--background-3) font-black">/</span>
                            <span class="text-(--text-primary) font-bold"
                                x-text="
                                    @foreach ($categories as $category)
                                        @foreach ($category->children as $child)
                                            categoryId == '{{ $child->id }}' ? '{{ $child->name }}' : @endforeach
                                    @endforeach ''
                                "></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="title" class="text-xs text-(--text-muted)">{{ __('messages.title') }}</label>
                        <input id="title" type="text" name="title" x-model="title"
                            value="{{ old('title') }}" autocomplete="off"
                            class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all
                             @error('title') @enderror">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="description"
                            class="text-xs text-(--text-muted)">{{ __('messages.description') }}</label>
                        <textarea id="description" name="description" rows="3"
                            class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all resize-none
                             @error('description') @enderror">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="city" class="text-xs text-(--text-muted)">{{ __('messages.city') }}</label>
                        <input id="city" type="text" name="city" x-model="city" value="{{ old('city') }}"
                            class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all
                             @error('city') @enderror">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs text-(--text-muted)">{{ __('messages.currency') }}</label>
                        <div class="flex gap-1 p-1 rounded-sm bg-(--background-2) border border-(--background-3) w-fit">
                            <template x-for="c in currencies" :key="c">
                                <button type="button" @click="currency = c"
                                    :class="currency === c ?
                                        'bg-(--button) text-(--button-text)' :
                                        'text-(--text-muted) hover:text-(--text-primary)'"
                                    class="px-4 py-1.5 rounded-sm text-xs font-bold transition-all" x-text="c">
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex flex-col gap-1 flex-1">
                            <label for="price_per_day" class="text-xs text-(--text-muted)">
                                {{ __('messages.price_per_day') }} (<span x-text="currency"></span>)
                            </label>
                            <div class="relative">
                                <input id="price_per_day" type="number" name="price_per_day"
                                    value="{{ old('price_per_day') }}" x-model="pricePerDay"
                                    class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all @error('price_per_day') @enderror">
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-(--text-muted) pointer-events-none"
                                    x-text="currency"></span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1 flex-1">
                            <label for="price_per_hour" class="text-xs text-(--text-muted)">
                                {{ __('messages.price_per_hour') }} (<span x-text="currency"></span>) <span
                                    class="opacity-40">{{ __('messages.optional') }}</span>
                            </label>
                            <div class="relative">
                                <input id="price_per_hour" type="number" name="price_per_hour"
                                    value="{{ old('price_per_hour') }}"
                                    class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-(--text-muted) pointer-events-none"
                                    x-text="currency"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="deposit" class="text-xs text-(--text-muted)">
                            {{ __('messages.deposit') }} (<span x-text="currency"></span>) <span
                                class="opacity-40">{{ __('messages.optional') }}</span>
                        </label>
                        <div class="relative">
                            <input id="deposit" type="number" name="deposit" value="{{ old('deposit') }}"
                                class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                            <span
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-(--text-muted) pointer-events-none"
                                x-text="currency"></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 pt-1" x-data="{ delivery: {{ old('delivery_available') ? 'true' : 'false' }} }">
                        <label class="flex items-center gap-2.5 text-sm text-(--text-muted) cursor-pointer select-none">
                            <input type="checkbox" name="delivery_available" value="1" x-model="delivery"
                                {{ old('delivery_available') ? 'checked' : '' }} class="accent-(--button)">
                            {{ __('messages.delivery_available') }}
                        </label>

                        <div x-show="delivery" x-transition class="flex flex-col gap-1">
                            <label for="delivery_price" class="text-xs text-(--text-muted)">
                                {{ __('messages.delivery_price') }} (<span x-text="$root.currency"></span>)
                            </label>
                            <div class="relative">
                                <input id="delivery_price" type="number" name="delivery_price"
                                    value="{{ old('delivery_price') }}"
                                    class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-(--text-muted) pointer-events-none"
                                    x-text="$root.currency"></span>
                            </div>
                        </div>
                    </div>

                    <label class="flex items-center gap-2.5 text-sm text-(--text-muted) cursor-pointer select-none">
                        <input type="checkbox" name="requires_document" value="1"
                            {{ old('requires_document') ? 'checked' : '' }} class="accent-(--button)">
                        {{ __('messages.requires_document') }}
                    </label>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="prevStep()"
                            class="flex-1 py-2.5 text-sm font-bold rounded-sm border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) hover:border-(--text-muted) transition-all cursor-pointer">
                            {{ __('messages.back') }}
                        </button>
                        <button type="button" @click="nextStep()" :disabled="!canProceedStep2"
                            :class="canProceedStep2
                                ?
                                'bg-(--button) text-(--button-text) hover:bg-(--button-h) cursor-pointer' :
                                'bg-(--background-3) text-(--text-muted) cursor-not-allowed opacity-50'"
                            class="flex-1 py-2.5 text-sm font-bold rounded-sm transition-all active:scale-95">
                            {{ __('messages.continue') }}
                        </button>
                    </div>

                </div>
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex flex-col gap-4">

                    <div class="flex flex-col gap-1">
                        <p class="text-sm font-semibold text-(--text-primary)">{{ __('messages.upload_photos') }}</p>
                        <p class="text-xs text-(--text-muted)">{{ __('messages.upload_photos_sub') }}</p>
                    </div>

                    <label
                        class="flex flex-col items-center justify-center gap-2 border border-dashed border-(--background-3) rounded-sm py-10 cursor-pointer hover:border-(--text-muted) transition-all bg-(--background-2)"
                        x-data="{ count: 0 }" @change="count = $event.target.files.length">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-(--text-muted)" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        <span class="text-xs text-(--text-muted)"
                            x-text="count > 0 ? count + ' photo(s) selected' : 'Click to upload'"></span>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden">
                    </label>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="prevStep()"
                            class="flex-1 py-2.5 text-sm font-bold rounded-sm border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) hover:border-(--text-muted) transition-all cursor-pointer">
                            {{ __('messages.back') }}
                        </button>
                        <button type="submit"
                            class="flex-1 py-2.5 text-sm font-bold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h) transition-all active:scale-95 cursor-pointer">
                            {{ __('messages.publish_listing') }}
                        </button>
                    </div>

                </div>

            </form>
        </div>
    </section>
@endsection
