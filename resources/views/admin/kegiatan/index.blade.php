<x-app-layout>
    <x-slot name="header">
        <div>

            <h2 class="font-display text-xl font-semibold text-slate-900">Manajemen Kegiatan</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Daftar Kegiatan</h3>
                    <p class="mt-2 text-sm text-slate-500">Simpan kegiatan yang nanti dipakai untuk absensi dan rekap laporan.</p>
                </div>
                <a href="{{ route('admin.kegiatan.create') }}" class="btn-primary h-10 px-4">Tambah Kegiatan</a>
            </div>

            <form method="GET" class="mt-6 grid gap-4 md:grid-cols-[1fr_auto]">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control mt-0"
                    placeholder="Cari nama kegiatan, lokasi, atau deskripsi"
                >
                <button type="submit" class="btn-secondary h-10 px-4">Filter</button>
            </form>
        </section>

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Ditugaskan</th>
                            <th>Deskripsi</th>
                            <th class="w-52">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan as $item)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $item->nama_kegiatan }}</td>
                                <td>{{ $item->tanggal->format('d M Y') }}</td>
                                <td>{{ $item->lokasi }}</td>
                                <td>{{ $item->assigned_users_count }}</td>
                                <td class="max-w-md text-slate-600">{{ \Illuminate\Support\Str::limit($item->deskripsi, 120) }}</td>
                                <td class="whitespace-nowrap align-middle">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        <a href="{{ route('admin.kegiatan.show', $item) }}" class="btn-secondary h-10 px-4">Detail</a>
                                        <a href="{{ route('admin.kegiatan.edit', $item) }}" class="btn-secondary h-10 px-4">Edit</a>
                                        <form method="POST" action="{{ route('admin.kegiatan.destroy', $item) }}" onsubmit="return confirm('Hapus kegiatan ini?')" data-loading-form>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger h-10 px-4" data-loading-label="Menghapus...">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Belum ada kegiatan yang sesuai dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $kegiatan->links() }}
    </div>
</x-app-layout>
