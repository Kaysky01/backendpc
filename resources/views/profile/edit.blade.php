<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="page-caption">Akun</p>
            <h2 class="page-title">Profil Saya</h2>
        </div>
    </x-slot>

    @php
        $anggota = $user->anggota;
    @endphp

    @if (! $user->hasVerifiedEmail())
        <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="hidden">
            @csrf
        </form>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="section-card">
            <h3 class="font-display text-2xl font-semibold text-slate-900">Informasi Akun</h3>
            <p class="mt-2 text-sm text-slate-500">Perbarui data akun utama dan pantau status verifikasi email Anda.</p>

            <div class="mt-6 grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required placeholder="Masukkan nama lengkap" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required placeholder="nama@polinela.ac.id" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="md:col-span-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Status Verifikasi Email</p>
                            <div class="mt-2">
                                @if ($user->hasVerifiedEmail())
                                    <x-badge variant="success">Email sudah diverifikasi</x-badge>
                                @else
                                    <x-badge variant="warning">Email belum diverifikasi</x-badge>
                                @endif
                            </div>
                        </div>

                        @if (! $user->hasVerifiedEmail())
                            <button type="submit" form="send-verification" class="btn-primary">Kirim verifikasi email</button>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="section-card">
            <h3 class="font-display text-2xl font-semibold text-slate-900">Informasi Anggota</h3>
            <p class="mt-2 text-sm text-slate-500">Lengkapi data akademik dan detail keanggotaan tanpa mengubah tampilan halaman lain.</p>

            <div class="mt-6 grid gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="npm" value="NPM" />
                    <x-text-input id="npm" name="npm" type="text" :value="old('npm', $anggota?->npm)" placeholder="2315xxxxxxx" />
                    <x-input-error :messages="$errors->get('npm')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="angkatan" value="Angkatan" />
                    <x-text-input id="angkatan" name="angkatan" type="text" :value="old('angkatan', $anggota?->angkatan)" placeholder="2024" />
                    <x-input-error :messages="$errors->get('angkatan')" class="mt-2" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="prodi" value="Program Studi" />
                    <x-text-input id="prodi" name="prodi" type="text" :value="old('prodi', $anggota?->prodi)" placeholder="Teknologi Informasi" />
                    <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="divisi" value="Divisi" />
                    <x-text-input id="divisi" name="divisi" type="text" :value="old('divisi', $user->divisi)" placeholder="Divisi desain, media, dokumentasi" />
                    <x-input-error :messages="$errors->get('divisi')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="role_detail" value="Role Detail" />
                    <x-text-input id="role_detail" name="role_detail" type="text" :value="old('role_detail', $user->role_detail)" placeholder="Koordinator, anggota aktif, PIC, dst." />
                    <x-input-error :messages="$errors->get('role_detail')" class="mt-2" />
                </div>
            </div>
        </section>

        <section class="section-card">
            <h3 class="font-display text-2xl font-semibold text-slate-900">Update Password</h3>
            <p class="mt-2 text-sm text-slate-500">Kosongkan password jika tidak ingin mengubahnya.</p>

            <div class="mt-6 grid gap-5 md:grid-cols-2">
                <div>
                    <x-input-label for="password" value="Password Baru" />
                    <x-text-input id="password" name="password" type="password" placeholder="Minimal 6 karakter" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password baru" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end border-t border-slate-100 pt-5">
                <x-primary-button>Simpan Perubahan</x-primary-button>
            </div>
        </section>
    </form>
</x-app-layout>
