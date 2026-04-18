<x-guest-layout>
    <div class="auth-card">
        <div class="mb-8">
            <x-badge variant="success">Masuk ke Sistem</x-badge>
            <h2 class="mt-4 font-display text-3xl font-semibold text-slate-900">Selamat datang kembali</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Gunakan akun admin atau anggota untuk membuka dashboard yang sesuai dengan peran Anda.
            </p>
        </div>

        <x-auth-session-status class="mb-5" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@polinela.ac.id" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label for="remember_me" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <input id="remember_me" type="checkbox" class="checkbox-control" name="remember">
                <span class="text-sm font-medium text-slate-600">Ingat sesi login saya</span>
            </label>

            <div class="flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2 text-sm">
                    @if (Route::has('password.request'))
                        <a class="font-semibold text-slate-600 transition hover:text-sky-600" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                    <p class="text-slate-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-sky-600 transition hover:text-sky-700">Daftar sebagai anggota</a>
                    </p>
                </div>

                <x-primary-button>
                    Log In
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
