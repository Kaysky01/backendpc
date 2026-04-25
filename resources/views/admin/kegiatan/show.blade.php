<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Detail Kegiatan</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                <div class="space-y-4">
                    <div>
                        <h3 class="font-display text-2xl font-semibold text-slate-900">{{ $kegiatan->nama_kegiatan }}</h3>
                        <p class="mt-2 text-sm text-slate-500">{{ $kegiatan->tanggal->format('d M Y') }} • {{ $kegiatan->lokasi }}</p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="info-tile rounded-2xl">
                            <p class="text-sm font-medium text-slate-500">Deskripsi</p>
                            <p class="mt-2 text-sm leading-6 text-slate-700">{{ $kegiatan->deskripsi }}</p>
                        </div>
                        <div class="info-tile rounded-2xl">
                            <p class="text-sm font-medium text-slate-500">Kode Absensi Terbaru</p>
                            @if ($kegiatan->latestCode)
                                <p class="mt-2 font-display text-xl font-semibold text-slate-900">{{ $kegiatan->latestCode->kode }}</p>
                                <p class="mt-2 text-sm text-slate-500">Expired {{ $kegiatan->latestCode->expired_at->format('d M Y H:i') }} ({{ $kegiatan->latestCode->expired_minutes }} menit)</p>
                            @else
                                <p class="mt-2 text-sm text-slate-500">Belum ada kode absensi untuk kegiatan ini.</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($kegiatan->latestCode)
                    <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                        <p class="text-sm font-medium text-slate-500">QR Kode</p>
                        <div class="mt-3 flex justify-center">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->generate($kegiatan->latestCode->kode) !!}
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Total Ditugaskan</p>
                <p class="stat-value">{{ $stats['total_ditugaskan'] }}</p>
            </div>
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Total Hadir</p>
                <p class="stat-value">{{ $stats['total_hadir'] }}</p>
            </div>
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Total Alfa</p>
                <p class="stat-value">{{ $stats['total_alfa'] }}</p>
            </div>
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Total Tidak Ditugaskan</p>
                <p class="stat-value">{{ $stats['total_tidak_ditugaskan'] }}</p>
            </div>
        </div>

        <section class="table-shell">
            <div class="border-b border-slate-100 px-6 py-5">
                <h3 class="font-display text-2xl font-semibold text-slate-900">Anggota Ditugaskan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Divisi</th>
                            <th>Status</th>
                            <th>Waktu Absen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignedMembers as $item)
                            @php
                                $statusClass = match ($item['status']) {
                                    'hadir' => 'badge-success',
                                    'izin' => 'badge-warning',
                                    'tidak_ditugaskan', 'belum_absen' => 'badge-neutral',
                                    default => 'badge-danger',
                                };
                            @endphp
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $item['user']->name }}</td>
                                <td>{{ $item['user']->divisi ?? '-' }}</td>
                                <td><span class="badge {{ $statusClass }}">{{ str($item['status'])->replace('_', ' ')->title() }}</span></td>
                                <td>{{ $item['waktu_absen'] ? $item['waktu_absen']->format('d M Y H:i:s') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500">Belum ada anggota yang ditugaskan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="section-card">
                <h3 class="font-display text-2xl font-semibold text-slate-900">Anggota Hadir</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($hadirMembers as $item)
                        <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                            <p class="font-semibold text-slate-900">{{ $item['user']->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $item['user']->divisi ?? 'Divisi belum diisi' }}</p>
                        </div>
                    @empty
                        <div class="empty-state">Belum ada anggota hadir.</div>
                    @endforelse
                </div>
            </section>

            <section class="section-card">
                <h3 class="font-display text-2xl font-semibold text-slate-900">Anggota Alfa</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($alfaMembers as $item)
                        <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                            <p class="font-semibold text-slate-900">{{ $item['user']->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $item['user']->divisi ?? 'Divisi belum diisi' }}</p>
                        </div>
                    @empty
                        <div class="empty-state">Belum ada anggota alfa.</div>
                    @endforelse
                </div>
            </section>

            <section class="section-card">
                <h3 class="font-display text-2xl font-semibold text-slate-900">Tidak Ditugaskan</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($tidakDitugaskanMembers as $user)
                        <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $user->divisi ?? 'Divisi belum diisi' }}</p>
                        </div>
                    @empty
                        <div class="empty-state">Semua anggota sedang ditugaskan.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
