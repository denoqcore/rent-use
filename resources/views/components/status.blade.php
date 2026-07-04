@props(['status'])

@php
    $colors = [
        'active' => 'var(--status-ok)',
        'confirmed' => 'var(--status-ok)',
        'pending' => 'var(--status-warning)',
        'paused' => 'var(--status-warning)',
        'cancelled' => 'var(--status-danger)',
        'completed' => 'var(--status-muted)',
        'archived' => 'var(--status-muted)',
    ];

    $color = $colors[$status] ?? 'var(--status-muted)';
    $key = 'messages.status_' . $status;
    $label = \Illuminate\Support\Facades\Lang::has($key) ? __($key) : ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 text-xs font-medium text-(--text-muted)']) }}>
    <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background: {{ $color }}"></span>
    {{ $label }}
</span>
