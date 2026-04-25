<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Manajemen Anggota</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Daftar Anggota</h3>
                    <p class="mt-2 text-sm text-slate-500">
                    
                    </p>
                </div>

                <!-- FIXED AREA -->
                <div class="flex flex-wrap items-end gap-3">
                    <form method="POST" action="{{ route('admin.anggota.import') }}" enctype="multipart/form-data" class="flex items-end gap-3">
                        @csrf

                        <input
                            type="file"
                            name="file"
                            accept=".xlsx,.xls,.csv"
                            required
                            class="form-control h-10 mt-0 max-w-xs"
                        >

                        <button type="submit" class="btn-secondary h-10 px-4">
                            Import
                        </button>
                    </form>

                    <a href="{{ route('admin.anggota.create') }}" class="btn-primary h-10 px-4 flex items-center">
                        Tambah Anggota
                    </a>
                </div>
            </div>

            <form method="GET" class="mt-6 grid gap-4 md:grid-cols-[1fr_auto]">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control mt-0 h-10"
                    placeholder="Cari nama, email, NPM, prodi, angkatan, divisi, atau role detail"
                >
                <button type="submit" class="btn-secondary h-10 px-4">Filter</button>
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
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.anggota.edit', $item) }}"
                                            class="btn-secondary action-btn px-3 h-9 flex items-center justify-center text-sm">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('admin.anggota.destroy', $item) }}"
                                                onsubmit="return confirm('Hapus anggota ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn-danger action-btn px-3 h-9 flex items-center justify-center text-sm">
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