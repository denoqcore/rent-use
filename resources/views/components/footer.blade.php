<?php

use Livewire\Component;

new class extends Component {};
?>

<footer class="bg-(--background-4) border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="pt-8 border-t border-black/35">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('storage/images/logo.svg') }}" alt="use.pls" class="h-5 w-auto">
                    <span class="text-[10px] text-gray-500">© 2026 All rights reserved</span>
                </div>

                <div class="flex items-center gap-6">
                    <a href="#"
                        class="text-[10px] text-(--text-muted) hover:text-gray-900 transition-colors">Privacy
                        Policy</a>
                    <a href="#"
                        class="text-[10px] text-(--text-muted) hover:text-gray-900 transition-colors">Terms of
                        Service</a>
                    <a href="#"
                        class="text-[10px] text-(--text-muted) hover:text-gray-900 transition-colors">Cookie
                        Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>
