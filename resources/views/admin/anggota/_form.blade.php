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
        <x-input-label for="divisi" value="Divisi" />
        <x-text-input id="divisi" name="divisi" type="text" :value="old('divisi', $anggota?->divisi)" placeholder="Divisi desain, media, dokumentasi" />
        <x-input-error :messages="$errors->get('divisi')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="role_detail" value="Role Detail" />
        <x-text-input id="role_detail" name="role_detail" type="text" :value="old('role_detail', $anggota?->role_detail)" placeholder="Koordinator, anggota aktif, PIC, dst." />
        <x-input-error :messages="$errors->get('role_detail')" class="mt-2" />
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

<div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
    <a href="{{ route('admin.anggota.index') }}" class="btn-secondary w-full sm:w-auto">Kembali</a>
    <x-primary-button class="w-full sm:w-auto">{{ $submitLabel }}</x-primary-button>
</div>
