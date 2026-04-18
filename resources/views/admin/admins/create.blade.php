<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Super Admin</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Tambah Admin</h2>
        </div>
    </x-slot>

    <section class="section-card">
        <h3 class="font-display text-2xl font-semibold text-slate-900">Formulir Admin Baru</h3>
        <p class="mt-2 text-sm text-slate-500">Tentukan apakah akun ini perlu menjadi super admin.</p>

        <form method="POST" action="{{ route('admin.admins.store') }}" class="mt-6">
            @csrf
            @include('admin.admins._form', ['admin' => null, 'submitLabel' => 'Simpan Admin'])
        </form>
    </section>
</x-app-layout>
