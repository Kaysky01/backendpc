<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-display text-xl font-semibold text-slate-900">Rekap Absensi</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="section-card">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-display text-2xl font-semibold text-slate-900">Filter Rekap</h3>
                    <p class="mt-2 text-sm text-slate-500">Pilih rentang 1, 3, atau 6 bulan lalu ekspor laporan ke PDF.</p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    @foreach ([1, 3, 6] as $item)
                        <a
                            href="{{ route('admin.reports.index', ['period' => $item]) }}"
                            class="{{ $period === $item ? 'btn-active' : 'btn-secondary' }} w-full sm:w-auto"
                        >
                            {{ $item }} Bulan
                        </a>
                    @endforeach
                    <a href="{{ route('admin.reports.export', ['period' => $period]) }}" class="btn-primary w-full sm:w-auto">
                        Export PDF
                    </a>
                </div>
            </div>
        </section>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Total Anggota</p>
                <p class="stat-value">{{ number_format($report['totals']['total_anggota']) }}</p>
            </div>
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Total Kegiatan</p>
                <p class="stat-value">{{ number_format($report['totals']['total_kegiatan']) }}</p>
            </div>
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Total Record</p>
                <p class="stat-value">{{ number_format($report['totals']['total_absensi']) }}</p>
            </div>
            <div class="stats-card">
                <p class="text-sm font-medium text-slate-500">Attendance Rate</p>
                <p class="stat-value">{{ number_format($report['totals']['attendance_percentage'], 2) }}%</p>
            </div>
        </div>

        <section class="table-shell">
            <div class="border-b border-slate-100 px-4 py-4 sm:px-6 sm:py-5">
                <h3 class="font-display text-2xl font-semibold text-slate-900">Ringkasan Per Anggota</h3>
                <p class="mt-2 text-sm text-slate-500">Rekap dihitung sejak {{ $report['from_date']->format('d M Y') }}.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th>Anggota</th>
                            <th>NPM</th>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Alfa</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report['summary'] as $item)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $item['user']->name }}</td>
                                <td>{{ $item['user']->anggota?->npm ?? '-' }}</td>
                                <td>{{ $item['hadir'] }}</td>
                                <td>{{ $item['izin'] }}</td>
                                <td>{{ $item['alfa'] }}</td>
                                <td>{{ number_format($item['persentase'], 2) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Belum ada data rekap untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>


    </div>
</x-app-layout>
