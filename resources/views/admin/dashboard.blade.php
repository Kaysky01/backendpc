<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-slate-500">Admin Panel</p>
            <h2 class="font-display text-3xl font-semibold text-slate-900">Dashboard Kehadiran</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="stats-card">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Total Anggota</p>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                        <x-icon name="users" class="h-5 w-5" />
                    </span>
                </div>
                <p class="stat-value">{{ number_format($total_anggota) }}</p>
                <p class="mt-3 text-sm text-slate-500">Akun anggota aktif dalam sistem.</p>
            </div>

            <div class="stats-card">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Total Kegiatan</p>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                        <x-icon name="calendar" class="h-5 w-5" />
                    </span>
                </div>
                <p class="stat-value">{{ number_format($total_kegiatan) }}</p>
                <p class="mt-3 text-sm text-slate-500">Semua kegiatan yang sudah tercatat.</p>
            </div>

            <div class="stats-card">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Total Absensi</p>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                        <x-icon name="clipboard" class="h-5 w-5" />
                    </span>
                </div>
                <p class="stat-value">{{ number_format($total_absensi) }}</p>
                <p class="mt-3 text-sm text-slate-500">Termasuk hadir, izin, dan alfa manual.</p>
            </div>

            <div class="stats-card">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Attendance Rate</p>
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                        <x-icon name="chart-bar" class="h-5 w-5" />
                    </span>
                </div>
                <p class="stat-value">{{ number_format($attendance_percentage, 2) }}%</p>
                <p class="mt-3 text-sm text-slate-500">Presentase kehadiran hadir terhadap total peluang absensi.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
            <section class="section-card">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Chart</p>
                        <h3 class="mt-1 font-display text-2xl font-semibold text-slate-900">Kehadiran Bulanan</h3>
                    </div>
                    <x-badge variant="info">6 Bulan Terakhir</x-badge>
                </div>

                <div class="mt-6 h-[340px]">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </section>

            <section class="section-card">
                <div>
                    <p class="text-sm font-medium text-slate-500">Activity Feed</p>
                    <h3 class="mt-1 font-display text-2xl font-semibold text-slate-900">Absensi Terbaru</h3>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($recent_absensi as $item)
                        @php
                            $statusClass = match ($item->status) {
                                'hadir' => 'badge-success',
                                'izin' => 'badge-warning',
                                default => 'badge-danger',
                            };
                        @endphp
                        <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $item->user->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $item->kegiatan->nama_kegiatan }} • {{ $item->kegiatan->tanggal->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="badge {{ $statusClass }}">{{ strtoupper($item->status) }}</span>
                            </div>
                            <p class="mt-3 text-sm text-slate-600">
                                Waktu absen: {{ $item->waktu_absen->format('d M Y H:i:s') }}
                            </p>
                        </div>
                    @empty
                        <div class="empty-state">
                            Belum ada data absensi terbaru.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('attendanceChart');

            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chart_labels),
                        datasets: [{
                            label: 'Jumlah Hadir',
                            data: @json($chart_values),
                            borderRadius: 12,
                            backgroundColor: [
                                '#e0f2fe',
                                '#bae6fd',
                                '#7dd3fc',
                                '#0ea5e9',
                                '#0284c7',
                                '#0369a1',
                            ],
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                },
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.18)',
                                },
                            },
                            x: {
                                grid: {
                                    display: false,
                                },
                            },
                        },
                    },
                });
            }
        </script>
    @endpush
</x-app-layout>
