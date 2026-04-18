<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Tambah Kegiatan</h2>
        </div>
    </x-slot>

    <section class="section-card">
        <h3 class="font-display text-2xl font-semibold text-slate-900">Form Kegiatan Baru</h3>
        <p class="mt-2 text-sm text-slate-500">Buat kegiatan yang nantinya dapat menghasilkan kode absensi.</p>

        <form method="POST" action="{{ route('admin.kegiatan.store') }}" class="mt-6">
            @csrf
            @include('admin.kegiatan._form', ['kegiatan' => null, 'submitLabel' => 'Simpan Kegiatan'])
        </form>
    </section>
</x-app-layout>
