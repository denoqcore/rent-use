<?php

use Livewire\Component;

new class extends Component {};
?>

<div>

    <header class="border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">

            <a href="/" class="font-bold text-lg">
                Rental
            </a>

            <div class="flex items-center gap-4">

                <a href="{{ route('lang.switch', 'en') }}">EN</a>
                <a href="{{ route('lang.switch', 'ro') }}">RO</a>

                <button
                    @click="
                        dark = !dark;
                        localStorage.setItem('dark', dark);
                        document.documentElement.classList.toggle('dark')
                    "
                    class="px-3 py-1 border rounded-lg">
                    🌙
                </button>

            </div>
        </div>
    </header>
</div>
