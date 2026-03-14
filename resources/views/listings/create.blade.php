@extends('layouts.layout')

@section('title', 'rent.use | Create listing')

@section('content')
    <section class="min-h-[calc(100vh-72px)] w-full py-12 bg-(--background)">
        <div class="max-w-lg mx-auto px-6 flex flex-col gap-4">

            <h1 class="text-xl font-black text-(--text-primary)">Create listing</h1>

            @if ($errors->any())
                <div class="text-sm text-red-400">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-3" x-data="{
                    parent: '{{ old('parent_category') }}',
                    categoryId: '{{ old('category_id') }}',
                }">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">Description</label>
                    <textarea name="description" rows="3"
                        class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none resize-none">{{ old('description') }}</textarea>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">Category</label>
                    <select x-model="parent" name="parent_category"
                        class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                @foreach ($categories as $category)
                    <div class="flex flex-col gap-1" x-show="parent == '{{ $category->id }}'">
                        <label class="text-xs text-(--text-muted)">Subcategory</label>
                        <select x-bind:disabled="parent != '{{ $category->id }}'"
                            x-bind:name="parent == '{{ $category->id }}' ? 'category_id' : ''" x-model="categoryId"
                            class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none">
                            <option value="">Select subcategory</option>
                            @foreach ($category->children as $child)
                                <option value="{{ $child->id }}"
                                    {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                    {{ $child->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">City</label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none">
                </div>

                <div class="flex gap-3">
                    <div class="flex flex-col gap-1 flex-1">
                        <label class="text-xs text-(--text-muted)">Price / day (MDL)</label>
                        <input type="number" name="price_per_day" value="{{ old('price_per_day') }}"
                            class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none">
                    </div>
                    <div class="flex flex-col gap-1 flex-1">
                        <label class="text-xs text-(--text-muted)">Price / hour (MDL) <span
                                class="opacity-40">optional</span></label>
                        <input type="number" name="price_per_hour" value="{{ old('price_per_hour') }}"
                            class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none">
                    </div>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">Deposit (MDL) <span
                            class="opacity-40">optional</span></label>
                    <input type="number" name="deposit" value="{{ old('deposit') }}"
                        class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none">
                </div>

                <label class="flex items-center gap-2 text-sm text-(--text-muted) cursor-pointer">
                    <input type="checkbox" name="delivery_available" value="1"
                        {{ old('delivery_available') ? 'checked' : '' }}>
                    Delivery available
                </label>

                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">Delivery price (MDL)</label>
                    <input type="number" name="delivery_price" value="{{ old('delivery_price') }}"
                        class="bg-(--background-2) border border-(--background-3) text-(--text-primary) text-sm px-3 py-2 rounded-sm focus:outline-none">
                </div>

                <label class="flex items-center gap-2 text-sm text-(--text-muted) cursor-pointer">
                    <input type="checkbox" name="requires_document" value="1"
                        {{ old('requires_document') ? 'checked' : '' }}>
                    Require ID / license
                </label>

                <div class="flex flex-col gap-1">
                    <label class="text-xs text-(--text-muted)">Photos <span class="opacity-40">max 8</span></label>
                    <input type="file" name="images[]" multiple accept="image/*" class="text-sm text-(--text-muted)">
                </div>

                <button type="submit"
                    class="mt-2 px-6 py-2.5 text-sm font-bold rounded-sm bg-(--button) text-(--button-text) hover:bg-(--button-h) transition-all cursor-pointer">
                    Publish listing
                </button>

            </form>
        </div>
    </section>
@endsection
