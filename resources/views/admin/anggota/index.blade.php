<x-app-layout>
    <x-slot name="header">
        <div>

            <h2 class="font-display text-xl font-semibold text-slate-900">Manajemen Anggota</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Daftar Anggota</h3>
                    <p class="mt-2 text-sm text-slate-500">
                    
                    </p>
                </div>

                <div class="flex w-full flex-col md:flex-row md:items-center md:justify-between gap-3 md:w-auto">
                    <form
                        method="POST"
                        action="{{ route('admin.anggota.import') }}"
                        enctype="multipart/form-data"
                        class="flex flex-col gap-3 md:flex-row md:items-center"
                        data-loading-form
                    >
                        @csrf

                        <input
                            type="file"
                            name="file"
                            accept=".xlsx,.xls,.csv"
                            required
                            class="file-control w-full md:w-[18rem]"
                        >

                        <button type="submit" class="btn-secondary w-full md:w-auto" data-loading-label="Mengimpor...">
                            Import
                        </button>
                    </form>

                    <a href="{{ route('admin.anggota.create') }}" class="btn-primary w-full md:w-auto">
                        Tambah Anggota
                    </a>
                </div>
            </div>

            <form method="GET" class="mt-6 grid gap-4 md:grid-cols-[1fr_auto]">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control mt-0"
                    placeholder="Cari nama, email, NPM, prodi, angkatan, divisi, atau role detail"
                >
                <button type="submit" class="btn-secondary w-full md:w-auto">Filter</button>
            </form>

            <p class="mt-3 text-xs text-slate-500">
                Format import: `name | email | password | divisi | role_detail`.
            </p>
        </section>

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>NPM</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th>Divisi</th>
                            <th>Role Detail</th>
                            <th >Email</th>
                            <th class="w-44">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotas as $item)
                            <tr>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $item->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Terdaftar {{ $item->created_at->format('d M Y') }}
                                    </p>
                                </td>
                                <td>{{ $item->anggota?->npm ?? '-' }}</td>
                                <td>{{ $item->anggota?->prodi ?? '-' }}</td>
                                <td>{{ $item->anggota?->angkatan ?? '-' }}</td>
                                <td>{{ $item->divisi ?? '-' }}</td>
                                <td>{{ $item->role_detail ?? '-' }}</td>
                                <td>{{ $item->email }}</td>
                                <td class="whitespace-nowrap align-middle">
                                        <div class="flex items-center justify-center gap-2 flex-col sm:flex-row">
                                            <a href="{{ route('admin.anggota.edit', $item) }}"
                                            class="btn-secondary w-full sm:w-auto">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('admin.anggota.destroy', $item) }}"
                                                onsubmit="return confirm('Hapus anggota ini?')"
                                                data-loading-form>
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                class="btn-danger w-full sm:w-auto"
                                                        data-loading-label="Menghapus...">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-slate-500">
                                    Belum ada anggota yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $anggotas->links() }}
    </div>
</x-app-layout>
