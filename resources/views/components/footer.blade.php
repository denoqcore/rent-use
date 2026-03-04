<?php

use Livewire\Component;

new class extends Component {};
?>
<footer class="bg-(--background-4) border-gray-200">
    <div
        class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-linear-to-r from-transparent via-(--background-3) to-transparent opacity-30">
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="pt-8 border-t border-black/35">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex justify-center items-center gap-2">
                    <div>
                        <a href="/" class="text-xl font-black tracking-wide">
                            rent<span class=" font-normal">.use</span>
                        </a>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-500">© 2026 {{ __('messages.all-rights') }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <a href="#" class="text-[10px] text-gray-900 hover:text-gray-700 transition-colors">
                        {{ __('messages.privacy') }}
                    </a>
                    <a href="#" class="text-[10px] text-gray-900 hover:text-gray-700 transition-colors">
                        {{ __('messages.terms') }}
                    </a>
                    <a href="#" class="text-[10px] text-gray-900 hover:text-gray-700 transition-colors">
                        {{ __('messages.cookies') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
