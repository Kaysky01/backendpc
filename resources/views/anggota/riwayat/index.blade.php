<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Anggota Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Riwayat Absensi</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Periode Riwayat</h3>
                    <p class="mt-2 text-sm text-slate-500">Tinjau kehadiran Anda dalam 1, 3, atau 6 bulan terakhir.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach ([1, 3, 6] as $item)
                        <a href="{{ route('anggota.riwayat.index', ['period' => $item]) }}" class="{{ $period === $item ? 'btn-primary' : 'btn-secondary' }}">
                            {{ $item }} Bulan
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="grid gap-5 md:grid-cols-3">
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Hadir</p>
                <p class="stat-value">{{ $stats['hadir'] }}</p>
            </div>
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Izin</p>
                <p class="stat-value">{{ $stats['izin'] }}</p>
            </div>
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Alfa</p>
                <p class="stat-value">{{ $stats['alfa'] }}</p>
            </div>
        </div>

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Waktu Absen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $item)
                            @php
                                $statusClass = match ($item['status']) {
                                    'hadir' => 'badge-success',
                                    'izin' => 'badge-warning',
                                    'alfa' => 'badge-danger',
                                    default => 'badge-neutral',
                                };
                            @endphp
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $item['kegiatan']->nama_kegiatan }}</td>
                                <td>{{ $item['kegiatan']->tanggal->format('d M Y') }}</td>
                                <td><span class="badge {{ $statusClass }}">{{ strtoupper($item['status']) }}</span></td>
                                <td>{{ $item['waktu_absen'] ? $item['waktu_absen']->format('d M Y H:i:s') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500">Belum ada riwayat pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $history->links() }}
    </div>
</x-app-layout>
