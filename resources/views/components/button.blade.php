@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $tag = $href ? 'a' : 'button';
    $classes = match ($variant) {
        'active' => 'btn-active',
        'secondary' => 'btn-secondary',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };
@endphp

<{{ $tag }}
    @if ($href)
        href="{{ $href }}"
    @else
        type="{{ $type }}"
    @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</{{ $tag }}>
