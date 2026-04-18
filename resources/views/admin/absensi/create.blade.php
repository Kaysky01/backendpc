<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Tambah Absensi Manual</h2>
        </div>
    </x-slot>

    <section class="section-card">
        <h3 class="font-display text-2xl font-semibold text-slate-900">Input Data Absensi</h3>
        <p class="mt-2 text-sm text-slate-500">Gunakan form ini untuk mencatat hadir, izin, atau alfa secara manual.</p>

        <form method="POST" action="{{ route('admin.absensi.store') }}" class="mt-6">
            @csrf
            @include('admin.absensi._form', ['absensiItem' => null, 'submitLabel' => 'Simpan Absensi'])
        </form>
    </section>
</x-app-layout>
