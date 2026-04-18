@props([
    'name',
])

@switch($name)
    @case('home')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.25 12 11.204 3.046a1.5 1.5 0 0 1 2.092 0L22.25 12M4.5 9.75V19.5a.75.75 0 0 0 .75.75h4.5v-5.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v5.25h4.5a.75.75 0 0 0 .75-.75V9.75" />
        </svg>
        @break
    @case('users')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M18 18.72a8.94 8.94 0 0 0-6-2.22 8.94 8.94 0 0 0-6 2.22M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.5 8.97a7.5 7.5 0 0 0-4.5-2.67 5.25 5.25 0 1 0-7.5-4.8" />
        </svg>
        @break
    @case('shield-check')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="m9 12.75 2.25 2.25L15 9.75m6 2.25c0 5.147-3.867 9.591-9 10.438C6.867 21.591 3 17.147 3 12V5.741c0-.388.238-.737.598-.868l7.5-2.727a.75.75 0 0 1 .514 0l7.5 2.727A.924.924 0 0 1 21 5.74V12Z" />
        </svg>
        @break
    @case('calendar')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 5.25h15a.75.75 0 0 1 .75.75v12a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75V6a.75.75 0 0 1 .75-.75Z" />
        </svg>
        @break
    @case('key')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15.75 5.25a3 3 0 1 1-4.35 2.673l-6.087 6.087a1.5 1.5 0 0 0-.44 1.061v1.179a1.5 1.5 0 0 0 1.5 1.5h1.179a1.5 1.5 0 0 0 1.06-.44l.97-.97h1.663a.75.75 0 0 0 .75-.75V13.92l.97-.97a3 3 0 0 0 2.78-7.7Z" />
        </svg>
        @break
    @case('clipboard')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5.25H7.5A2.25 2.25 0 0 0 5.25 7.5v10.125A2.625 2.625 0 0 0 7.875 20.25h8.25a2.625 2.625 0 0 0 2.625-2.625V7.5A2.25 2.25 0 0 0 16.5 5.25H15M9 5.25A2.25 2.25 0 0 1 11.25 3h1.5A2.25 2.25 0 0 1 15 5.25M9 5.25A2.25 2.25 0 0 0 11.25 7.5h1.5A2.25 2.25 0 0 0 15 5.25" />
        </svg>
        @break
    @case('document')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.5 14.25v-8.25A2.25 2.25 0 0 0 17.25 3.75h-10.5A2.25 2.25 0 0 0 4.5 6v12A2.25 2.25 0 0 0 6.75 20.25h6.75m6-6-3-3m0 0-3 3m3-3V21" />
        </svg>
        @break
    @case('clock')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6v6l4 2.25M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        @break
    @case('bars-3')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
        @break
    @case('chevron-down')
        <svg {{ $attributes->merge(['viewBox' => '0 0 20 20', 'fill' => 'currentColor']) }}>
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
        </svg>
        @break
    @case('user-circle')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        @break
    @case('logout')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
        </svg>
        @break
    @case('plus')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        @break
    @case('filter')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.5 6h9.75M3.75 6h3.75m3 12h9.75m-16.5 0h3.75m1.5-6h12.75m-18.75 0h2.25" />
        </svg>
        @break
    @case('chart-bar')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 13.125h6.75V21H3v-7.875Zm11.25-9h6.75V21h-6.75V4.125ZM8.625 8.625h6.75V21h-6.75V8.625Z" />
        </svg>
        @break
    @case('check-circle')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        @break
    @case('exclamation-triangle')
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="m11.25 9 0 3.75M12 16.5h.008v.008H12V16.5Zm8.294 1.094-7.5-13.5a.75.75 0 0 0-1.308 0l-7.5 13.5A.75.75 0 0 0 4.64 18.75h14.72a.75.75 0 0 0 .654-1.156Z" />
        </svg>
        @break
    @default
        <svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor']) }}>
            <circle cx="12" cy="12" r="8" stroke-width="1.75" />
        </svg>
@endswitch
