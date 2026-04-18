@php
    $anggotaProfile = $anggota?->anggota;
@endphp

<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-input-label for="name" value="Nama Lengkap" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $anggota?->name)" required placeholder="Masukkan nama lengkap" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" :value="old('email', $anggota?->email)" required placeholder="nama@polinela.ac.id" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="npm" value="NPM" />
        <x-text-input id="npm" name="npm" type="text" :value="old('npm', $anggotaProfile?->npm)" required placeholder="2315xxxxxxx" />
        <x-input-error :messages="$errors->get('npm')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="angkatan" value="Angkatan" />
        <x-text-input id="angkatan" name="angkatan" type="text" :value="old('angkatan', $anggotaProfile?->angkatan)" required placeholder="2024" />
        <x-input-error :messages="$errors->get('angkatan')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="prodi" value="Program Studi" />
        <x-text-input id="prodi" name="prodi" type="text" :value="old('prodi', $anggotaProfile?->prodi)" required placeholder="Teknologi Informasi" />
        <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password" :value="$anggota ? 'Password Baru (Opsional)' : 'Password'" />
        <x-text-input id="password" name="password" type="password" :required="! $anggota" placeholder="Minimal 8 karakter" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" :required="! $anggota" placeholder="Ulangi password" />
    </div>
</div>

<div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
    <a href="{{ route('admin.anggota.index') }}" class="btn-secondary">Kembali</a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
