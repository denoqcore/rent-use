<?php

use Livewire\Component;

new class extends Component {};
?>
<footer class="bg-(--background) border-t border-(--background-3)">
    <div class="max-w-6xl mx-auto px-6 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">

            <div class="flex items-center gap-3">
                <a href="/" class="text-sm flex items-center gap-1 font-black tracking-wide text-(--text-primary)">
                    <div>
                        <img src="{{ asset('storage/images/logo.svg') }}" alt="rent.use" class="w-4 h-8">
                    </div>
                    <div>
                        rent<span class="text-(--text-muted) font-normal">.use</span>
                    </div>
                </a>
                <span class="w-px h-3 bg-(--background-3)"></span>
                <span class="text-xs text-(--text-muted)">© 2026 {{ __('messages.all-rights') }}</span>
            </div>

            <div class="flex items-center gap-6">
                <a href="/" class="text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors">
                    {{ __('messages.support') }}
                </a>
                <a href="/" class="text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors">
                    FAQ
                </a>

                <a href="#" class="text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors">
                    {{ __('messages.privacy') }}
                </a>
                <a href="#" class="text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors">
                    {{ __('messages.terms') }}
                </a>
                <a href="#" class="text-xs text-(--text-muted) hover:text-(--text-primary) transition-colors">
                    {{ __('messages.cookies') }}
                </a>
            </div>

        </div>
    </div>
</footer>
