<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Polinela Creative Attendance System') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|sora:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="min-h-screen bg-slate-50">
            <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-5 py-6 sm:px-8 lg:px-10">
                <header class="flex items-center justify-between rounded-2xl border border-gray-200 bg-white px-5 py-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-sm">
                            <x-application-logo class="h-6 w-6 fill-current" />
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">PCAS</p>
                            <h1 class="font-display text-lg font-semibold text-slate-900">Polinela Creative Attendance System</h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-button variant="secondary" :href="route('login')">Login</x-button>
                        <x-button :href="route('register')">Register</x-button>
                    </div>
                </header>

                <main class="flex flex-1 flex-col items-center justify-center py-20 px-6">
                    <section class="text-center max-w-4xl">
                        <x-badge variant="info">Attendance Management</x-badge>
                        <h2 class="mt-6 max-w-3xl font-display text-5xl font-semibold leading-tight text-slate-900 sm:text-6xl mx-auto">
                            Sistem absensi kegiatan kreatif yang cepat, aman, dan mudah dipantau.
                        </h2>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 mx-auto">
                            Admin dapat mengelola anggota, kegiatan, kode absensi aktif 15 menit, monitoring data hadir, dan ekspor rekap PDF. Anggota cukup login, lihat kegiatan, masukkan kode, dan pantau riwayat lengkap dengan timestamp presisi.
                        </p>

                        <div class="mt-8 flex flex-wrap justify-center gap-4">
                            <x-button :href="route('login')">Masuk ke Dashboard</x-button>
                            <x-button variant="secondary" :href="route('register')">Daftar Anggota</x-button>
                        </div>
                    </section>

                    <section class="mt-16 flex items-center justify-center">
                        <div class="flex h-64 w-64 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-lg">
                            <x-application-logo class="h-24 w-24 fill-current" />
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </body>
</html>
