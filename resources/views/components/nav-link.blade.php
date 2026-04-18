@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-b-2 border-sky-500 px-1 pt-1 text-sm font-medium leading-5 text-slate-900 transition duration-150 ease-in-out focus:border-sky-600 focus:outline-none'
            : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-slate-500 transition duration-150 ease-in-out hover:border-gray-300 hover:text-slate-700 focus:border-gray-300 focus:outline-none focus:text-slate-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
