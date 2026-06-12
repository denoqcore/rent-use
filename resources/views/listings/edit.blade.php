@extends('layouts.layout')

@section('title', 'rent.use | Edit listing')

@section('content')
    <section class="min-h-[calc(100vh-72px)] mt-12 lg:mt-20 md:pt-20 w-full py-12 bg-(--background)">
        <div class="max-w-xl mx-auto px-6 flex flex-col gap-8" x-data="{
            parent: '{{ $listing->category->parent_id ?? $listing->category_id }}',
            categoryId: '{{ $listing->category_id }}',
            currency: '{{ $listing->currency }}',
            currencies: ['MDL', 'EUR', 'USD'],
            pricePerDay: '{{ $listing->price_per_day ?? '' }}',
            pricePerHour: '{{ $listing->price_per_hour ?? '' }}',
            delivery: {{ $listing->delivery_available ? 'true' : 'false' }},
            deliveryPrice: '{{ $listing->delivery_price ?? '' }}',
            cityId: '{{ $listing->city_id }}',
            cityName: '{{ $listing->city->name }}',
            citySearch: '',
            cityOpen: false,
            cities: {{ \Illuminate\Support\Js::from($cities->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'is_suburb' => $c->is_suburb])) }},
            get filteredCities() {
                return this.cities.filter(c => c.name.toLowerCase().includes(this.citySearch.toLowerCase()));
            },
            selectCity(city) {
                this.cityId = city.id;
                this.cityName = city.name;
                this.cityOpen = false;
                this.citySearch = '';
            },
            pricingMode: '{{ $listing->price_per_day && $listing->price_per_hour ? 'both' : ($listing->price_per_day ? 'day' : 'hour') }}',
            newImages: [],
            deleteImages: [],
            isSubmitting: false,
            handleFiles(event) {
                const files = Array.from(event.target.files);
                const remaining = 8 - {{ $listing->images->count() }} - this.newImages.length;
                files.slice(0, remaining).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => this.newImages.push({ src: e.target.result, file });
                    reader.readAsDataURL(file);
                });
                event.target.value = '';
            },
            removeNew(index) { this.newImages.splice(index, 1); },
            toggleDelete(id) {
                const i = this.deleteImages.indexOf(id);
                i === -1 ? this.deleteImages.push(id) : this.deleteImages.splice(i, 1);
            },
            isMarkedDelete(id) { return this.deleteImages.includes(id); }
        }" x-cloak>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-black text-(--text-primary)">Edit listing</h1>
                    <p class="text-xs text-(--text-muted) mt-1">{{ $listing->title }}</p>
                </div>
                <a href="{{ route('listings.show', $listing->slug) }}"
                    class="text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors">
                    ← Back
                </a>
            </div>

            @if ($errors->any())
                <div class="p-4 rounded-sm border border-red-500 bg-red-500/10">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-500">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('listings.update', $listing->slug) }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-6"
                @submit="
                isSubmitting = true;
                const dt = new DataTransfer();
                newImages.forEach(img => dt.items.add(img.file));
                $refs.fileInput.files = dt.files;
            ">
                @csrf
                @method('PATCH')
                <input type="hidden" name="currency" :value="currency">
                <div class="flex flex-col gap-2">
                    <label class="text-xs text-(--text-muted)">Category</label>
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
                        <div x-show="parent == '{{ $category->id }}'">
                            <p class="text-xs text-(--text-muted) mb-2">Subcategory</p>
                            <div class="flex flex-col gap-1">
                                @foreach ($category->children as $child)
                                    <button type="button" @click="categoryId = '{{ $child->id }}'"
                                        :class="categoryId == '{{ $child->id }}' ?
                                            'border-(--button) text-(--text-primary)' :
                                            'border-(--background-3) text-(--text-muted) hover:border-(--text-muted)'"
                                        class="flex items-center justify-between px-4 py-2.5 rounded-sm border bg-(--background-2) text-sm transition-all text-left cursor-pointer">
                                        <span>{{ $child->name }}</span>
                                        <svg x-show="categoryId == '{{ $child->id }}'" class="w-4 h-4 text-(--button)"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    <input type="hidden" name="category_id" :value="categoryId">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">Title</label>
                    <input type="text" name="title" value="{{ old('title', $listing->title) }}" autocomplete="off"
                        class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">Description</label>
                    <textarea name="description" rows="4"
                        class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none focus:border-(--text-muted) transition-all resize-none">{{ old('description', $listing->description) }}</textarea>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">City</label>
                    <div class="relative">
                        <button type="button"
                            @click="cityOpen = !cityOpen; if(cityOpen) $nextTick(() => $refs.citySearch.focus())"
                            class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none text-left flex justify-between items-center">
                            <span x-text="cityName || 'Select city'"></span>
                            <svg class="w-4 h-4 text-(--text-muted)" :class="cityOpen ? 'rotate-180' : ''" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="cityOpen" x-cloak @click.away="cityOpen = false"
                            class="absolute z-50 w-full mt-1 bg-(--background-2) border border-(--background-3) rounded-sm shadow-xl overflow-hidden">
                            <div class="p-2 border-b border-(--background-3)">
                                <input type="text" x-model="citySearch" x-ref="citySearch" placeholder="Search..."
                                    class="w-full bg-(--background) border border-(--background-3) text-xs text-(--text-primary) px-2 py-1.5 rounded-sm focus:outline-none">
                            </div>
                            <div class="max-h-52 overflow-y-auto">
                                <template x-for="city in filteredCities.filter(c => !c.is_suburb)" :key="city.id">
                                    <div @click="selectCity(city)" class="px-3 py-2 text-sm cursor-pointer"
                                        :class="cityId == city.id ? 'bg-(--button)/10 text-(--button) font-medium' :
                                            'text-(--text-muted) hover:bg-(--background-3)'">
                                        <span x-text="city.name"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="city_id" :value="cityId">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-xs text-(--text-muted)">Currency</label>
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
                    <label class="text-xs text-(--text-muted)">Pricing type</label>
                    <div class="flex gap-1 p-1 rounded-sm bg-(--background-2) border border-(--background-3) w-fit">
                        <button type="button" @click="pricingMode = 'day'; pricePerHour = ''"
                            :class="pricingMode === 'day' ? 'bg-(--button) text-(--button-text)' : 'text-(--text-muted)'"
                            class="px-4 py-1.5 rounded-sm text-xs font-bold transition-all cursor-pointer">Per day</button>
                        <button type="button" @click="pricingMode = 'hour'; pricePerDay = ''"
                            :class="pricingMode === 'hour' ? 'bg-(--button) text-(--button-text)' : 'text-(--text-muted)'"
                            class="px-4 py-1.5 rounded-sm text-xs font-bold transition-all cursor-pointer">Per
                            hour</button>
                        <button type="button" @click="pricingMode = 'both'"
                            :class="pricingMode === 'both' ? 'bg-(--button) text-(--button-text)' : 'text-(--text-muted)'"
                            class="px-4 py-1.5 rounded-sm text-xs font-bold transition-all cursor-pointer">Both</button>
                    </div>
                </div>

                <div class="flex gap-4 items-start">
                    <div class="flex flex-col gap-1 min-w-35" x-show="pricingMode === 'day' || pricingMode === 'both'">
                        <label class="text-xs text-(--text-muted)">Price / day</label>
                        <div class="relative max-w-35">
                            <input type="number" min="0" x-model="pricePerDay"
                                :name="pricingMode === 'hour' ? '' : 'price_per_day'"
                                class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none">
                            <span
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-(--text-muted) font-bold"
                                x-text="currency"></span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1 min-w-35" x-show="pricingMode === 'hour' || pricingMode === 'both'">
                        <label class="text-xs text-(--text-muted)">Price / hour</label>
                        <div class="relative max-w-35">
                            <input type="number" min="0" x-model="pricePerHour"
                                :name="pricingMode === 'day' ? '' : 'price_per_hour'"
                                class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none">
                            <span
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-(--text-muted) font-bold"
                                x-text="currency"></span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-1 max-w-35">
                    <label class="text-xs text-(--text-muted)">Deposit</label>
                    <div class="relative">
                        <input type="number" min="0" name="deposit"
                            value="{{ old('deposit', $listing->deposit) }}"
                            class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-(--text-muted) font-bold"
                            x-text="currency"></span>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-2.5 text-sm text-(--text-muted) cursor-pointer select-none">
                        <input type="checkbox" name="delivery_available" value="1" x-model="delivery"
                            class="accent-(--button)">
                        Delivery available
                    </label>
                    <div x-show="delivery" x-transition class="flex flex-col gap-1 max-w-35">
                        <label class="text-xs text-(--text-muted)">Delivery price</label>
                        <div class="relative">
                            <input type="number" min="0" name="delivery_price"
                                value="{{ old('delivery_price', $listing->delivery_price) }}"
                                class="w-full bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 pr-12 rounded-sm focus:outline-none">
                            <span
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-(--text-muted) font-bold"
                                x-text="currency"></span>
                        </div>
                    </div>
                </div>
                <label class="flex items-center gap-2.5 text-sm text-(--text-muted) cursor-pointer select-none">
                    <input type="checkbox" name="requires_document" value="1"
                        {{ $listing->requires_document ? 'checked' : '' }} class="accent-(--button)">
                    Document required
                </label>
                <div class="flex flex-col gap-3">
                    <label class="text-xs text-(--text-muted) uppercase tracking-wide font-semibold">Photos</label>
                    @if ($listing->images->isNotEmpty())
                        <div class="grid grid-cols-4 gap-2">
                            @foreach ($listing->images as $image)
                                <div class="relative aspect-square rounded-sm border border-(--background-3) overflow-hidden group cursor-pointer"
                                    :class="isMarkedDelete({{ $image->id }}) ? 'opacity-40 border-red-400' : ''"
                                    @click="toggleDelete({{ $image->id }})">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                        style="background: rgba(0,0,0,0.4)">
                                        <template x-if="!isMarkedDelete({{ $image->id }})">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </template>
                                        <template x-if="isMarkedDelete({{ $image->id }})">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                        </template>
                                    </div>
                                    @if ($image->is_main)
                                        <span
                                            class="absolute bottom-1 left-1 text-[9px] font-bold bg-black/60 text-white px-1.5 py-0.5 rounded-sm">Main</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-(--text-muted)">Click a photo to mark for deletion</p>
                        <template x-for="id in deleteImages" :key="id">
                            <input type="hidden" name="delete_images[]" :value="id">
                        </template>
                    @endif

                    <div class="grid grid-cols-4 gap-2" x-show="newImages.length > 0">
                        <template x-for="(img, index) in newImages" :key="index">
                            <div class="relative aspect-square rounded-sm border border-(--background-3) bg-cover bg-center group"
                                :style="'background-image: url(' + img.src + ')'">
                                <button type="button" @click="removeNew(index)"
                                    class="absolute top-1 right-1 w-5 h-5 rounded-full bg-black/60 text-white text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    ×
                                </button>
                            </div>
                        </template>
                    </div>

                    <label
                        class="flex flex-col items-center justify-center gap-2 border border-dashed border-(--background-3) rounded-sm py-8 cursor-pointer hover:border-(--text-muted) transition-all bg-(--background-2)">
                        <svg class="w-5 h-5 text-(--text-muted)" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-xs text-(--text-muted)">Add photos</span>
                        <input type="file" multiple accept="image/*" class="hidden" @change="handleFiles">
                    </label>

                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" x-ref="fileInput">
                </div>

                <div class="flex gap-2 pb-10">
                    <a href="{{ route('listings.show', $listing->slug) }}"
                        class="flex-1 py-2.5 text-sm font-bold rounded-sm border border-(--background-3) text-(--text-muted) hover:text-(--text-primary) transition-all text-center">
                        Cancel
                    </a>
                    <button type="submit" :disabled="isSubmitting"
                        :class="isSubmitting ? 'bg-(--background-3) cursor-not-allowed' : 'bg-(--button) hover:bg-(--button-h)'"
                        class="flex-1 py-2.5 text-sm font-bold rounded-sm text-(--button-text) transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <template x-if="isSubmitting">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </template>
                        <span x-text="isSubmitting ? 'Saving...' : 'Save changes'"></span>
                    </button>
                </div>

            </form>
        </div>
    </section>
@endsection
