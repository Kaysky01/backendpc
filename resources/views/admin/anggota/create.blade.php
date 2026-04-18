<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Tambah Anggota</h2>
        </div>
    </x-slot>

    <section class="section-card">
        <h3 class="font-display text-2xl font-semibold text-slate-900">Formulir Anggota Baru</h3>
        <p class="mt-2 text-sm text-slate-500">Lengkapi identitas akun dan data akademik anggota.</p>

        <form method="POST" action="{{ route('admin.anggota.store') }}" class="mt-6">
            @csrf
            @include('admin.anggota._form', ['anggota' => null, 'submitLabel' => 'Simpan Anggota'])
        </form>
    </section>
</x-app-layout>
