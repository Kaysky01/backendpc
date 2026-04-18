<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Polinela Creative Attendance System') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|sora:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-12 sm:px-6">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-sm">
                        <x-application-logo class="h-7 w-7 fill-current" />
                    </div>
                    <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">PCAS</p>
                    <h1 class="mt-2 font-display text-2xl font-semibold text-slate-900">Polinela Creative Attendance System</h1>
                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Sistem absensi kegiatan dengan antarmuka sederhana, rapi, dan siap dipakai untuk operasional harian.
                    </p>
                </div>

                {{ $slot }}

                <p class="mt-6 text-center text-xs text-slate-400">
                    Laravel 13 • Blade • Tailwind CSS
                </p>
            </div>
        </div>
    </body>
</html>
