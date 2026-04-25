<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Monitoring Absensi</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Filter Kehadiran</h3>
                    <p class="mt-2 text-sm text-slate-500">Pantau dan koreksi absensi manual bila diperlukan.</p>
                </div>
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
    <form method="POST"
          action="{{ route('admin.absensi.import') }}"
          enctype="multipart/form-data"
          class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        @csrf

        <input type="file"
               name="file"
               class="form-control mt-0 w-full sm:max-w-xs"
               accept=".xlsx,.xls,.csv"
               required>

        <button type="submit"
                class="btn-secondary h-10 flex items-center justify-center whitespace-nowrap">
            Import Absensi
        </button>
    </form>

    <a href="{{ route('admin.absensi.create') }}"
       class="btn-primary h-10 flex items-center justify-center whitespace-nowrap">
        Tambah Absensi Manual
    </a>
</div>
            </div>

            <form method="GET" class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <select name="user_id" class="form-control mt-0">
                    <option value="">Semua anggota</option>
                    @foreach ($anggotaList as $item)
                        <option value="{{ $item->id }}" @selected($filters['user_id'] === $item->id)>
                            {{ $item->name }}{{ $item->anggota?->npm ? ' - '.$item->anggota->npm : '' }}
                        </option>
                    @endforeach
                </select>

                <select name="kegiatan_id" class="form-control mt-0">
                    <option value="">Semua kegiatan</option>
                    @foreach ($kegiatanList as $item)
                        <option value="{{ $item->id }}" @selected($filters['kegiatan_id'] === $item->id)>
                            {{ $item->nama_kegiatan }}
                        </option>
                    @endforeach
                </select>

                <select name="divisi" class="form-control mt-0">
                    <option value="">Semua divisi</option>
                    @foreach ($divisiList as $divisi)
                        <option value="{{ $divisi }}" @selected($filters['divisi'] === $divisi)>{{ $divisi }}</option>
                    @endforeach
                </select>

                <input type="date" name="tanggal" value="{{ $filters['tanggal'] }}" class="form-control mt-0">
                <button type="submit" class="btn-secondary">Terapkan Filter</button>
            </form>
            <p class="mt-3 text-xs text-slate-500">Format import: `nama_kegiatan | tanggal | lokasi | nama_user | email | status | waktu_absen`.</p>
        </section>

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>Kegiatan</th>
                            <th>Status</th>
                            <th>Tanggal Kegiatan</th>
                            <th>Waktu Absen</th>
                            <th class="w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                            @forelse ($absensi as $item)
                            @php
                                $statusClass = match ($item->status) {
                                    'hadir' => 'badge-success',
                                    'izin' => 'badge-warning',
                                    'tidak_ditugaskan' => 'badge-neutral',
                                    default => 'badge-danger',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $item->user->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $item->user->anggota?->npm ?? 'NPM belum tersedia' }}</p>
                                </td>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $item->kegiatan->nama_kegiatan }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $item->kegiatan->lokasi }}</p>
                                </td>
                                <td><span class="badge {{ $statusClass }}">{{ str($item->status)->replace('_', ' ')->title() }}</span></td>
                                <td>{{ $item->kegiatan->tanggal->format('d M Y') }}</td>
                                <td>{{ $item->waktu_absen->format('d M Y H:i:s') }}</td>
                                <td class="whitespace-nowrap align-middle text-center">
                                    <a href="{{ route('admin.absensi.edit', $item) }}" class="btn-secondary !px-4 !py-2">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Belum ada data absensi yang cocok dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $absensi->links() }}
    </div>
</x-app-layout>
