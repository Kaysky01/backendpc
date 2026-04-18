@props([
    'variant' => 'info',
])

@php
    $classes = match ($variant) {
        'neutral' => 'badge-neutral',
        'warning' => 'badge-warning',
        'danger' => 'badge-danger',
        'success' => 'badge-success',
        default => 'badge-info',
    };
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
