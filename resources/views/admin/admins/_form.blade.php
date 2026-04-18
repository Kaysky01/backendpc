<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-input-label for="name" value="Nama Admin" />
        <x-text-input id="name" name="name" type="text" :value="old('name', $admin?->name)" required placeholder="Nama admin" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" :value="old('email', $admin?->email)" required placeholder="admin@polinela.ac.id" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password" :value="$admin ? 'Password Baru (Opsional)' : 'Password'" />
        <x-text-input id="password" name="password" type="password" :required="! $admin" placeholder="Minimal 8 karakter" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" :required="! $admin" placeholder="Ulangi password" />
    </div>

    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 md:col-span-2">
        <input type="hidden" name="is_super_admin" value="0">
        <input
            type="checkbox"
            name="is_super_admin"
            value="1"
            class="checkbox-control"
            @checked(old('is_super_admin', $admin?->is_super_admin))
        >
        <div>
            <p class="font-semibold text-slate-900">Super Admin</p>
            <p class="text-sm text-slate-500">Berikan akses untuk mengelola akun admin lain.</p>
        </div>
    </label>
    <x-input-error :messages="$errors->get('is_super_admin')" class="md:col-span-2" />
</div>

<div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
    <a href="{{ route('admin.admins.index') }}" class="btn-secondary">Kembali</a>
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
</div>
