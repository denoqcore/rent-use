@extends('layouts.layout')

@section('title', 'rent.use | ' . __('messages.create_listing'))

@section('content')
    <section class="min-h-[calc(100vh-72px)] w-full py-12 bg-(--background)">
        <div class="max-w-xl mx-auto px-6 flex flex-col gap-8" x-data="{
            step: {{ $errors->any() ? 2 : 1 }},
            parent: '{{ old('parent_category') }}',
            categoryId: '{{ old('category_id') }}',
            currency: 'MDL',
            currencies: ['MDL', 'EUR', 'USD'],
            transportCategoryId: '1',
            pricePerDay: '',
            pricePerHour: '',
            delivery: false,
            deliveryPrice: '',
            deposit: '',
            cityId: '{{ old('city_id') }}',
            cityName: '{{ old('city_id') ? optional(\App\Models\Cities::find(old('city_id')))->name : '' }}',
            citySearch: '',
            cityOpen: false,
            cities: {{ \Illuminate\Support\Js::from($cities->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'is_suburb' => $c->is_suburb])) }},
            get filteredCities() {
                return this.cities.filter(c =>
                    c.name.toLowerCase().includes(this.citySearch.toLowerCase())
                );
            },
            selectCity(city) {
                this.cityId = city.id;
                this.cityName = city.name;
                this.cityOpen = false;
                this.citySearch = '';
            },
            pricingMode: 'day',
            title: '{{ old('title') }}',
            description: '{{ old('description') }}',
            isSubmitting: false,
            images: [],
        
            init() {
                @if(!$errors->any())
                if (sessionStorage.getItem('listing_draft')) {
                    let data = JSON.parse(sessionStorage.getItem('listing_draft'));
                    this.title = data.title || '';
                    this.description = data.description || '';
                }
                @endif
                this.$watch('title', v => this.saveDraft());
                this.$watch('description', v => this.saveDraft());
            },
            saveDraft() {
                sessionStorage.setItem('listing_draft', JSON.stringify({
                    title: this.title,
                    description: this.description,
                }));
            },
            handleFiles(event) {
                const files = Array.from(event.target.files);
                files.forEach(file => {
                    if (this.images.length >= 8) return;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.images.push({ src: e.target.result, file: file });
                    };
                    reader.readAsDataURL(file);
                });
                event.target.value = '';
            },
            removeImage(index) {
                this.images.splice(index, 1);
            },
            get canProceedStep1() { return this.parent !== '' && this.categoryId !== ''; },
            get canProceedStep2() {
                if (this.title.trim().length < 5) return false;
                if (!this.cityId) return false;
                if (this.pricingMode === 'day') return Number(this.pricePerDay) > 0;
                if (this.pricingMode === 'hour') return Number(this.pricePerHour) > 0;
                if (this.pricingMode === 'both') return Number(this.pricePerDay) > 0 && Number(this.pricePerHour) > 0;
                return false;
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
        }" x-cloak>

            <div>
                <h1 class="text-xl font-black text-(--text-primary)">{{ __('messages.create_listing') }}</h1>
                <p class="text-xs text-(--text-muted) mt-1">{{ __('messages.create_listing_sub') }}</p>
            </div>

            <div class="flex items-center gap-0">
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                        :class="step >= 1 ? 'bg-(--button) text-(--button-text)' :
                            'bg-(--background-2) text-(--text-muted) border border-(--background-3)'">
                        <template x-if="step > 1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step <= 1"><span>1</span></template>
                    </div>
                    <span class="text-[10px] font-medium transition-colors duration-300"
                        :class="step >= 1 ? 'text-(--text-primary)' : 'text-(--text-muted)'">{{ __('messages.step_category') }}</span>
                </div>
                <div class="flex-1 h-px mb-4 mx-2 transition-all duration-500"
                    :class="step >= 2 ? 'bg-(--button)' : 'bg-(--background-3)'"></div>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                        :class="step >= 2 ? 'bg-(--button) text-(--button-text)' :
                            'bg-(--background-2) text-(--text-muted) border border-(--background-3)'">
                        <template x-if="step > 2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step <= 2"><span>2</span></template>
                    </div>
                    <span class="text-[10px] font-medium transition-colors duration-300"
                        :class="step >= 2 ? 'text-(--text-primary)' : 'text-(--text-muted)'">{{ __('messages.step_details') }}</span>
                </div>
                <div class="flex-1 h-px mb-4 mx-2 transition-all duration-500"
                    :class="step >= 3 ? 'bg-(--button)' : 'bg-(--background-3)'"></div>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300"
                        :class="step >= 3 ? 'bg-(--button) text-(--button-text)' :
                            'bg-(--background-2) text-(--text-muted) border border-(--background-3)'">
                        <span>3</span>
                    </div>
                    <span class="text-[10px] font-medium transition-colors duration-300"
                        :class="step >= 3 ? 'text-(--text-primary)' : 'text-(--text-muted)'">{{ __('messages.step_photos') }}</span>
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
                class="flex flex-col gap-6"
                @submit="
                isSubmitting = true;
                sessionStorage.removeItem('listing_draft');
                const dt = new DataTransfer();
                images.forEach(img => dt.items.add(img.file));
                $refs.fileInput.files = dt.files;
                ">
                @csrf
                <input type="hidden" name="currency" :value="currency">

                <div x-show="step === 1" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex flex-col gap-5">
                    <div class="flex flex-col gap-2">
                        <p class="text-sm font-semibold text-(--text-primary)">{{ __('messages.choose_main_category') }}
                        </p>
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
                                    :class="parent == '{{ $category->id }}' ? 'bg-(--button)' : 'bg-(--background-3)'"></span>
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="parent_category" :value="parent">
                    @foreach ($categories as $category)
                        <div x-show="parent == '{{ $category->id }}'" class="flex flex-col gap-2">
                            <p class="text-sm font-semibold text-(--text-primary)">{{ __('messages.choose_subcategory') }}
                            </p>
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
                        :class="canProceedStep1 ? 'bg-(--button) text-(--button-text)' :
                            'bg-(--background-3) text-(--text-muted) opacity-50 cursor-not-allowed'"
                        class="w-full py-2.5 text-sm font-bold rounded-sm transition-all active:scale-95 cursor-pointer">{{ __('messages.continue') }}</button>
                </div>

                <div x-show="step === 2" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex flex-col gap-4">
                    <div class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-(--background-3)/30 border border-(--background-3)"
                        x-show="categoryId !== ''">
                        <x-heroicon-s-tag class="w-3 h-3 text-(--text-muted)/60" />
                        <div class="flex items-center gap-1.5 text-[11px] tracking-tight">
                            <span class="text-(--text-muted) font-medium"
                                x-text="@foreach ($categories as $category) parent == '{{ $category->id }}' ? '{{ $category->name }}' : @endforeach ''"></span>
                            <span class="text-(--background-3) font-black">/</span>
                            <span class="text-(--text-primary) font-bold"
                                x-text="@foreach ($categories as $category) @foreach ($category->children as $child) categoryId == '{{ $child->id }}' ? '{{ $child->name }}' : @endforeach @endforeach ''"></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-(--text-muted)">{{ __('messages.title') }}</label>
                        <input type="text" name="title" x-model="title" autocomplete="off"
                            class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-(--text-muted)">{{ __('messages.description') }}</label>
                        <textarea name="description" x-model="description" rows="3"
                            class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all resize-none"></textarea>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-(--text-muted)">{{ __('messages.city') }}</label>

                        <div class="relative">
                            <button type="button"
                                @click="cityOpen = !cityOpen; if(cityOpen) $nextTick(() => $refs.citySearch.focus())"
                                class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) text-left flex justify-between items-center">
                                <span x-text="cityName || '{{ __('messages.select_city') }}'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-(--text-muted)"
                                    :class="cityOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="cityOpen" x-cloak @click.away="cityOpen = false"
                                class="absolute z-50 w-full mt-1 bg-(--background-2) border border-(--background-3) rounded-sm shadow-xl overflow-hidden">

                                <div class="p-2 border-b border-(--background-3)">
                                    <input type="text" x-model="citySearch" x-ref="citySearch"
                                        placeholder="{{ __('messages.search') }}..."
                                        class="w-full bg-(--background) border border-(--background-3) text-xs text-(--text-primary) px-2 py-1.5 rounded-sm focus:outline-none focus:border-(--text-muted)">
                                </div>

                                <div class="max-h-52 overflow-y-auto">
                                    <template x-for="city in filteredCities.filter(c => !c.is_suburb)"
                                        :key="city.id">
                                        <div @click="selectCity(city)" class="px-3 py-2 text-sm cursor-pointer"
                                            :class="cityId == city.id ? 'bg-(--button)/10 text-(--button) font-medium' :
                                                'text-(--text-muted) hover:bg-(--background-3) hover:text-(--text-primary)'">
                                            <span x-text="city.name"></span>
                                        </div>
                                    </template>

                                    <template x-if="filteredCities.filter(c => c.is_suburb).length > 0">
                                        <div>
                                            <div
                                                class="px-3 py-1.5 text-[10px] font-semibold text-(--text-muted) uppercase tracking-widest border-t border-(--background-3) mt-1 pt-2">
                                                {{ __('messages.suburbs') }}
                                            </div>
                                            <template x-for="city in filteredCities.filter(c => c.is_suburb)"
                                                :key="city.id">
                                                <div @click="selectCity(city)" class="px-3 py-2 text-sm cursor-pointer"
                                                    :class="cityId == city.id ? 'bg-(--button)/10 text-(--button) font-medium' :
                                                        'text-(--text-muted) hover:bg-(--background-3) hover:text-(--text-primary)'">
                                                    <span x-text="city.name"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <div x-show="filteredCities.length === 0"
                                        class="px-3 py-4 text-xs text-(--text-muted) text-center">
                                        {{ __('messages.not_found') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="city_id" :value="cityId">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs text-(--text-muted)">{{ __('messages.currency') }}</label>
                        <div class="flex gap-1 p-1 rounded-sm bg-(--background-2) border border-(--background-3) w-fit">
                            <template x-for="c in currencies" :key="c">
                                <button type="button" @click="currency = c"
                                    :class="currency === c ? 'bg-(--button) text-(--button-text)' :
                                        'text-(--text-muted) hover:text-(--text-primary)'"
                                    class="px-4 py-1.5 rounded-sm text-xs font-bold transition-all cursor-pointer"
                                    x-text="c"></button>
                            </template>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-xs text-(--text-muted)">{{ __('messages.pricing_type') }}</label>
                        <div class="flex gap-1 p-1 rounded-sm bg-(--background-2) border border-(--background-3) w-fit">
                            <button type="button" @click="pricingMode = 'day'; pricePerHour = ''"
                                :class="pricingMode === 'day' ? 'bg-(--button) text-(--button-text)' :
                                    'text-(--text-muted)'"
                                class="px-4 py-1.5 rounded-sm text-xs font-bold transition-all cursor-pointer">{{ __('messages.per_day') }}</button>
                            <button type="button" @click="pricingMode = 'hour'; pricePerDay = ''"
                                :class="pricingMode === 'hour' ? 'bg-(--button) text-(--button-text)' :
                                    'text-(--text-muted)'"
                                class="px-4 py-1.5 rounded-sm text-xs font-bold transition-all cursor-pointer">{{ __('messages.per_hour') }}</button>
                            <button type="button" @click="pricingMode = 'both'"
                                :class="pricingMode === 'both' ? 'bg-(--button) text-(--button-text)' :
                                    'text-(--text-muted)'"
                                class="px-4 py-1.5 rounded-sm text-xs font-bold transition-all cursor-pointer">{{ __('messages.both') }}</button>
                        </div>
                    </div>

                    <div class="flex flex-row gap-4 items-start">
                        <div class="flex flex-col gap-1 min-w-35"
                            x-show="pricingMode === 'day' || pricingMode === 'both'">
                            <label class="text-xs text-(--text-muted)">{{ __('messages.price_per_day') }}</label>
                            <div class="relative max-w-35">
                                <input type="number" min="0"
                                    @keydown="if($event.key === '-' || $event.key === 'e') $event.preventDefault()"
                                    x-model="pricePerDay" :name="pricingMode === 'hour' ? '' : 'price_per_day'"
                                    class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none focus:border-(--text-muted)">
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-(--text-muted) font-bold"
                                    x-text="currency"></span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1 min-w-35"
                            x-show="pricingMode === 'hour' || pricingMode === 'both'">
                            <label class="text-xs text-(--text-muted)">{{ __('messages.price_per_hour') }}</label>
                            <div class="relative max-w-35">
                                <input type="number" min="0"
                                    @keydown="if($event.key === '-' || $event.key === 'e') $event.preventDefault()"
                                    x-model="pricePerHour" :name="pricingMode === 'day' ? '' : 'price_per_hour'"
                                    class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none focus:border-(--text-muted)">
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-(--text-muted) font-bold"
                                    x-text="currency"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 max-w-35">
                        <label class="text-xs text-(--text-muted)">{{ __('messages.deposit') }}</label>
                        <div class="relative">
                            <input type="number" min="0"
                                @keydown="if($event.key === '-' || $event.key === 'e') $event.preventDefault()"
                                name="deposit"
                                class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none focus:border-(--text-muted)">
                            <span
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-(--text-muted) font-bold"
                                x-text="currency"></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 pt-1">
                        <label class="flex items-center gap-2.5 text-sm text-(--text-muted) cursor-pointer select-none">
                            <input type="checkbox" name="delivery_available" value="1" x-model="delivery"
                                class="accent-(--button)">
                            {{ __('messages.delivery_available') }}
                        </label>
                        <div x-show="delivery" x-transition class="flex flex-col gap-1 max-w-35">
                            <label class="text-xs text-(--text-muted)">{{ __('messages.delivery_price') }}</label>
                            <div class="relative">
                                <input type="number" min="0" oninput="this.value = Math.abs(this.value)"
                                    name="delivery_price"
                                    class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none focus:border-(--text-muted)">
                                <span
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-(--text-muted) font-bold"
                                    x-text="currency"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="prevStep()"
                            class="flex-1 py-2.5 text-sm font-bold rounded-sm border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) transition-all cursor-pointer">{{ __('messages.back') }}</button>
                        <button type="button" @click="nextStep()" :disabled="!canProceedStep2"
                            :class="canProceedStep2 ? 'bg-(--button) text-(--button-text)' :
                                'bg-(--background-3) text-(--text-muted) opacity-50'"
                            class="flex-1 py-2.5 text-sm font-bold rounded-sm transition-all cursor-pointer">{{ __('messages.continue') }}</button>
                    </div>
                </div>

                <div x-show="step === 3" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="flex flex-col gap-4">

                    <div class="flex flex-col gap-1">
                        <p class="text-sm font-semibold text-(--text-primary)">{{ __('messages.upload_photos') }}</p>
                        <p class="text-xs text-(--text-muted)">{{ __('messages.upload_photos_sub') }}</p>
                    </div>

                    <label x-show="images.length === 0"
                        class="flex flex-col items-center justify-center gap-2 border border-dashed border-(--background-3) rounded-sm py-10 cursor-pointer hover:border-(--text-muted) transition-all bg-(--background-2)">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-(--text-muted)" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        <span class="text-xs text-(--text-muted)">{{ __('messages.click_to_upload') }}</span>
                        <input type="file" multiple accept="image/*" class="hidden" @change="handleFiles">
                    </label>

                    <div class="grid grid-cols-4 gap-2" x-show="images.length > 0">
                        <template x-for="(img, index) in images" :key="index">
                            <div class="relative aspect-square rounded-sm border border-(--background-3) bg-cover bg-center group"
                                :style="'background-image: url(' + img.src + ')'">
                                <button type="button" @click="removeImage(index)"
                                    class="absolute top-1 right-1 w-5 h-5 rounded-full bg-black/60 text-white text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    ×
                                </button>
                                <span x-show="index === 0"
                                    class="absolute bottom-1 left-1 text-[9px] font-bold bg-black/60 text-white px-1.5 py-0.5 rounded-sm">
                                    {{ __('messages.main') }}
                                </span>
                            </div>
                        </template>

                        <label x-show="images.length < 8"
                            class="aspect-square rounded-sm border border-dashed border-(--background-3) bg-(--background-2) flex items-center justify-center cursor-pointer hover:border-(--text-muted) transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-(--text-muted)" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            <input type="file" multiple accept="image/*" class="hidden" @change="handleFiles">
                        </label>
                    </div>

                    <p class="text-[10px] text-(--text-muted)" x-show="images.length > 0">
                        <span x-text="images.length"></span>/8 {{ __('messages.photos_selected') }}
                    </p>

                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" x-ref="fileInput">

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="prevStep()"
                            class="flex-1 py-2.5 text-sm font-bold rounded-sm border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) transition-all cursor-pointer"
                            :disabled="isSubmitting">
                            {{ __('messages.back') }}
                        </button>
                        <button type="submit" :disabled="isSubmitting"
                            :class="isSubmitting ? 'bg-(--background-3) cursor-not-allowed' :
                                'bg-(--button) hover:bg-(--button-h)'"
                            class="flex-1 py-2.5 text-sm font-bold rounded-sm text-(--button-text) transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <template x-if="isSubmitting">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </template>
                            <span
                                x-text="isSubmitting ? '{{ __('messages.publishing') }}' : '{{ __('messages.publish_listing') }}'"></span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </section>
@endsection
