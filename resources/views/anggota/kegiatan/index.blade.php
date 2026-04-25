<x-app-layout>
    <x-slot name="header">
        <div>
           
            <h2 class="font-display text-xl font-semibold text-slate-900">Daftar Kegiatan</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Kegiatan Tersedia</h3>
                    <p class="mt-2 text-sm text-slate-500">Periksa status apakah Anda sudah melakukan absensi pada kegiatan terkait.</p>
                </div>
                <a href="{{ route('anggota.absensi.create') }}" class="btn-primary">Input Kode Absensi</a>
            </div>

            <form method="GET" class="mt-6 grid gap-4 md:grid-cols-[1fr_auto]">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="form-control mt-0"
                    placeholder="Cari nama kegiatan, lokasi, atau deskripsi"
                >
                <button type="submit" class="btn-secondary">Filter</button>
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
                            <th>Status</th>
                            <th class="w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan as $item)
                            @php
                                $statusClass = match ($item['status']) {
                                    'hadir' => 'badge-success',
                                    'izin' => 'badge-warning',
                                    'tidak_ditugaskan', 'belum_absen' => 'badge-neutral',
                                    default => 'badge-danger',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $item['kegiatan']->nama_kegiatan }}</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ \Illuminate\Support\Str::limit($item['kegiatan']->deskripsi, 120) }}</p>
                                </td>
                                <td>{{ $item['kegiatan']->tanggal->format('d M Y') }}</td>
                                <td>{{ $item['kegiatan']->lokasi }}</td>
                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ str($item['status'])->replace('_', ' ')->title() }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('anggota.kegiatan.show', $item['kegiatan']) }}" class="btn-secondary !px-4 !py-2">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-slate-500">Belum ada kegiatan yang sesuai pencarian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $kegiatan->links() }}
    </div>
</x-app-layout>
