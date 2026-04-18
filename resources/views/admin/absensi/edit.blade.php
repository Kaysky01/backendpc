<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Edit Absensi</h2>
        </div>
    </x-slot>

    <section class="section-card">
        <h3 class="font-display text-2xl font-semibold text-slate-900">{{ $absensiItem->user->name }}</h3>
        <p class="mt-2 text-sm text-slate-500">
            Perbarui status absensi untuk kegiatan {{ $absensiItem->kegiatan->nama_kegiatan }}.
        </p>

        <form method="POST" action="{{ route('admin.absensi.update', $absensiItem) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.absensi._form', ['submitLabel' => 'Perbarui Absensi'])
        </form>
    </section>
</x-app-layout>
