<x-guest-layout>
    <div class="auth-card">
        <div class="mb-8">
            <x-badge variant="info">Registrasi Anggota</x-badge>
            <h2 class="mt-4 font-display text-3xl font-semibold text-slate-900">Buat akun anggota baru</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">
                Lengkapi data akademik dan akun Anda untuk mulai menggunakan sistem absensi kegiatan Polinela Creative.
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap anggota" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="npm" value="NPM" />
                    <x-text-input id="npm" type="text" name="npm" :value="old('npm')" required placeholder="2315xxxxxxx" />
                    <x-input-error :messages="$errors->get('npm')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="angkatan" value="Angkatan" />
                    <x-text-input id="angkatan" type="text" name="angkatan" :value="old('angkatan')" required placeholder="2024" />
                    <x-input-error :messages="$errors->get('angkatan')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="prodi" value="Program Studi" />
                    <x-text-input id="prodi" type="text" name="prodi" :value="old('prodi')" required placeholder="Teknologi Informasi" />
                    <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@polinela.ac.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-slate-500">
                    Sudah memiliki akun?
                    <a class="font-semibold text-sky-600 transition hover:text-sky-700" href="{{ route('login') }}">
                        Masuk di sini
                    </a>
                </p>

                <x-primary-button>
                    Register
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
