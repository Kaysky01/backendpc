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
        @stack('styles')
    </head>
    <body>
        @auth
            @php
                $statusMessage = match (session('status')) {
                    'profile-updated' => 'Profil berhasil diperbarui.',
                    'password-updated' => 'Password berhasil diperbarui.',
                    'verification-link-sent' => 'Link verifikasi telah dikirim ke email Anda.',
                    default => session('status'),
                };
            @endphp

            <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-50">
                <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/30 lg:hidden" @click="sidebarOpen = false"></div>

                <aside
                    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-gray-200 bg-white px-5 py-6 shadow-sm transition-transform duration-300 lg:translate-x-0"
                >
                    <a href="{{ route('dashboard') }}" class="mb-8 flex items-center gap-3 px-1">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-sm">
                            <x-application-logo class="h-6 w-6 fill-current" />
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-600">PCAS</p>
                            <h1 class="font-display text-base font-semibold text-slate-900">Polinela Creative Attendance System</h1>
                        </div>
                    </a>

                    @include('layouts.partials.sidebar')
                </aside>

                <div class="min-h-screen lg:pl-72">
                    <header class="sticky top-0 z-30 border-b border-gray-200 bg-white">
                        <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    class="topbar-action lg:hidden"
                                    @click="sidebarOpen = true"
                                >
                                    <span class="sr-only">Buka menu</span>
                                    <x-icon name="bars-3" class="h-5 w-5" />
                                </button>

                                <div>
                                    @isset($header)
                                        {{ $header }}
                                    @else
                                        <div>
                                            <p class="page-caption">Ringkasan</p>
                                            <h2 class="page-title">Dashboard</h2>
                                        </div>
                                    @endisset
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="hidden text-right sm:block">
                                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500">{{ ucfirst(auth()->user()->role) }}</p>
                                </div>

                                <x-dropdown align="right" width="56">
                                    <x-slot name="trigger">
                                        <button type="button" class="inline-flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-sm font-semibold text-sky-600">
                                                {{ \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->take(2)->map(fn ($item) => \Illuminate\Support\Str::substr($item, 0, 1))->implode('') }}
                                            </span>
                                            <x-icon name="chevron-down" class="h-4 w-4 text-slate-400" />
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <div class="border-b border-gray-200 px-4 py-3">
                                            <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                        </div>

                                        <div class="px-2 py-2">
                                            <x-dropdown-link :href="route('profile.edit')">
                                                Profil Saya
                                            </x-dropdown-link>
                                        </div>

                                        <div class="border-t border-gray-200 px-2 py-2">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-left text-sm font-medium text-red-600 transition hover:bg-red-50">
                                                    <x-icon name="logout" class="h-4 w-4" />
                                                    Keluar
                                                </button>
                                            </form>
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </header>

                    <main class="px-4 py-6 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </main>
                </div>

                <div class="pointer-events-none fixed inset-x-0 top-4 z-[60] flex flex-col items-center gap-3 px-4 sm:items-end sm:px-6">
                    @if (session('success'))
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="translate-y-2 opacity-0"
                            x-transition:enter-end="translate-y-0 opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            x-init="setTimeout(() => show = false, 3500)"
                            class="toast-panel pointer-events-auto border-sky-200"
                        >
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-sky-100 text-sky-600">
                                    <x-icon name="check-circle" class="h-4 w-4" />
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900">Berhasil</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ session('success') }}</p>
                                </div>
                                <button type="button" class="text-slate-400 transition hover:text-slate-600" @click="show = false">×</button>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="translate-y-2 opacity-0"
                            x-transition:enter-end="translate-y-0 opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            x-init="setTimeout(() => show = false, 4000)"
                            class="toast-panel pointer-events-auto border-red-200"
                        >
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-600">
                                    <x-icon name="exclamation-triangle" class="h-4 w-4" />
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900">Perlu perhatian</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ session('error') }}</p>
                                </div>
                                <button type="button" class="text-slate-400 transition hover:text-slate-600" @click="show = false">×</button>
                            </div>
                        </div>
                    @endif

                    @if ($statusMessage && ! session('success') && ! session('error'))
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="translate-y-2 opacity-0"
                            x-transition:enter-end="translate-y-0 opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            x-init="setTimeout(() => show = false, 3000)"
                            class="toast-panel pointer-events-auto border-sky-200"
                        >
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-sky-100 text-sky-600">
                                    <x-icon name="check-circle" class="h-4 w-4" />
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900">Informasi</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $statusMessage }}</p>
                                </div>
                                <button type="button" class="text-slate-400 transition hover:text-slate-600" @click="show = false">×</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{ $slot }}
        @endauth

        @stack('scripts')
    </body>
</html>
