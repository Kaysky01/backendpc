<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Edit Kegiatan</h2>
        </div>
    </x-slot>

    <section class="section-card">
        <h3 class="font-display text-2xl font-semibold text-slate-900">{{ $kegiatan->nama_kegiatan }}</h3>
        <p class="mt-2 text-sm text-slate-500">Perbarui detail jadwal, lokasi, dan deskripsi kegiatan.</p>

        <form method="POST" action="{{ route('admin.kegiatan.update', $kegiatan) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.kegiatan._form', ['submitLabel' => 'Perbarui Kegiatan'])
        </form>
    </section>
</x-app-layout>
