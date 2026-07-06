@php
    $message = session('success_info') ?? (session('success_password') ?? (session('success') ?? session('error')));
    $type = session('error') ? 'error' : 'success';
@endphp

@if ($message)
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed top-28 right-6 z-50 w-[380px] max-w-[calc(100vw-2rem)] rounded-lg border border-(--background-3) bg-(--background-2) shadow-lg">

        <div class="relative flex gap-3 p-4 pr-8">

            <div class="shrink-0 mt-0.5">
                @if ($type === 'success')
                    <x-heroicon-o-check-circle class="w-5 h-5 text-(--status-success)" />
                @else
                    <x-heroicon-o-exclamation-circle class="w-5 h-5 text-(--status-danger)" />
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-(--text-primary)">
                    {{ $type === 'success' ? __('messages.success') : __('messages.error') }}
                </p>
                <p class="mt-1 text-sm text-(--text-muted) leading-snug">
                    {{ $message }}
                </p>
            </div>

            <button @click="show = false"
                class="absolute top-3 right-3 rounded-md p-0.5 text-(--text-muted)/70 transition-colors hover:text-(--text-primary) cursor-pointer">
                <x-heroicon-o-x-mark class="w-3.5 h-3.5" />
            </button>

        </div>
    </div>
@endif
