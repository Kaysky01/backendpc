<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-caption">Dashboard</p>
            <h2 class="page-title">{{ __('Dashboard') }}</h2>
        </div>
    </x-slot>

    <x-card title="Welcome Back" description="Halaman ini merupakan fallback dashboard Laravel Breeze.">
        <p class="text-sm text-slate-600">{{ __("You're logged in!") }}</p>
    </x-card>
</x-app-layout>
