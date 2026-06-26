@php
    $message = session('success_info') ?? (session('success_password') ?? (session('success') ?? session('error')));
    $type = session('error') ? 'error' : 'success';
@endphp

@if ($message)
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed top-32 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-sm text-sm shadow-lg border
            {{ $type === 'error'
                ? 'bg-red-500/10 border-red-500/20 text-red-400'
                : 'bg-green-500/10 border-green-500/20 text-green-500' }}">
        @if ($type === 'error')
            <x-heroicon-o-x-circle class="w-4 h-4 shrink-0" />
        @else
            <x-heroicon-o-check-circle class="w-4 h-4 shrink-0" />
        @endif
        {{ $message }}
        <button @click="show = false" class="ml-2 opacity-50 hover:opacity-100 transition-opacity cursor-pointer">
            <x-heroicon-o-x-mark class="w-3.5 h-3.5" />
        </button>
    </div>
@endif
