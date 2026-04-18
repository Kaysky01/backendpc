<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Edit Anggota</h2>
        </div>
    </x-slot>

    <section class="section-card">
        <h3 class="font-display text-2xl font-semibold text-slate-900">{{ $anggota->name }}</h3>
        <p class="mt-2 text-sm text-slate-500">Perbarui data akun, akademik, dan password anggota bila diperlukan.</p>

        <form method="POST" action="{{ route('admin.anggota.update', $anggota) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.anggota._form', ['submitLabel' => 'Perbarui Anggota'])
        </form>
    </section>
</x-app-layout>
