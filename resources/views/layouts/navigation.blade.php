<nav x-data="{ open: false }" class="border-b border-gray-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-sm">
                        <x-application-logo class="h-5 w-5 fill-current" />
                    </span>
                    <span class="font-display text-lg font-semibold text-slate-900">PCAS</span>
                </a>

                <div class="hidden space-x-6 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-sm font-semibold text-sky-600">
                                {{ \Illuminate\Support\Str::substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <x-icon name="chevron-down" class="h-4 w-4 text-slate-400" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-gray-200 px-4 py-3">
                            <p class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="px-2 py-2">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        </div>
                        <div class="border-t border-gray-200 px-2 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-left text-sm font-medium text-red-600 transition hover:bg-red-50">
                                    <x-icon name="logout" class="h-4 w-4" />
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="topbar-action">
                    <x-icon name="bars-3" class="h-5 w-5" />
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="border-t border-gray-200 pt-4 pb-1">
            <div class="px-4">
                <div class="text-base font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                <div class="text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
