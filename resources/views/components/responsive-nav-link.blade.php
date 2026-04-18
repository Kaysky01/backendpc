@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-sky-500 bg-sky-50 py-2 pe-4 ps-3 text-start text-base font-medium text-sky-700 transition duration-150 ease-in-out focus:border-sky-600 focus:bg-sky-100 focus:text-sky-800 focus:outline-none'
            : 'block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-slate-600 transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-50 hover:text-slate-800 focus:border-gray-300 focus:bg-gray-50 focus:text-slate-800 focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
