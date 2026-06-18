<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Rekap Absensi {{ $period }} Bulan</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                color: #0f172a;
                font-size: 12px;
            }

            .title {
                margin-bottom: 18px;
            }

            .title h1 {
                margin: 0;
                font-size: 22px;
            }

            .title p {
                margin: 4px 0 0;
                color: #475569;
            }

            .summary-grid {
                width: 100%;
                margin-bottom: 18px;
            }

            .summary-grid td {
                width: 25%;
                padding: 12px;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
                vertical-align: top;
            }

            .summary-grid strong {
                display: block;
                margin-top: 8px;
                font-size: 18px;
            }

            table.data {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 18px;
            }

            table.data th,
            table.data td {
                border: 1px solid #cbd5e1;
                padding: 8px;
            }

            table.data th {
                background: #e2e8f0;
                text-align: left;
                font-size: 11px;
                text-transform: uppercase;
            }

            h2 {
                margin: 18px 0 10px;
                font-size: 16px;
            }
        </style>
    </head>
    <body>
        <div class="title">
            <h1>Polinela Creative Attendance System</h1>
            <p>Rekap absensi {{ $period }} bulan terakhir • Digenerate {{ $generatedAt->format('d M Y H:i:s') }}</p>
        </div>

        @php
            $chartLabels = [];
            $chartData = [];

            // Mengambil data anggota dan persentase kehadirannya
            $sortedSummary = collect($report['summary'])->sortByDesc('persentase');

            foreach ($sortedSummary as $item) {
                // Potong nama jika terlalu panjang
                $name = $item['user']->name;
                $chartLabels[] = strlen($name) > 15 ? substr($name, 0, 15) . '...' : $name;
                $chartData[] = $item['persentase'];
            }

            // Menghitung lebar ideal berdasarkan jumlah data agar nama tidak bertumpuk
            $dataCount = count($chartLabels);
            $chartWidth = max(700, $dataCount * 30); // Minimal 700px, atau bertambah agar batang tidak berdesakan

            $chartConfig = [
                'type' => 'bar', // Dikembalikan ke bar vertikal
                'data' => [
                    'labels' => $chartLabels,
                    'datasets' => [
                        [
                            'label' => 'Persentase Kehadiran (%)',
                            'data' => $chartData,
                            'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
                            'borderColor' => 'rgb(54, 162, 235)',
                            'borderWidth' => 1
                        ]
                    ]
                ],
                'options' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Grafik Persentase Kehadiran Anggota'
                    ],
                    'scales' => [
                        'yAxes' => [
                            [
                                'ticks' => [
                                    'beginAtZero' => true,
                                    'max' => 100
                                ]
                            ]
                        ],
                        'xAxes' => [
                            [
                                'ticks' => [
                                    'autoSkip' => false,
                                    'maxRotation' => 90, // Teks vertikal penuh agar menghemat ruang horizontal
                                    'minRotation' => 90,
                                    'fontSize' => 10
                                ],
                                // Pastikan sumbu X selalu tampil meskipun datanya 0
                                'gridLines' => [
                                    'display' => true,
                                    'drawOnChartArea' => false
                                ]
                            ]
                        ]
                    ],
                    'legend' => [
                        'display' => false
                    ]
                ]
            ];

            $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig)) . '&w=' . $chartWidth . '&h=350&format=png';
            $chartImage = base64_encode(file_get_contents($chartUrl));
        @endphp

        <div style="text-align: center; margin-bottom: 20px;">
            <img src="data:image/png;base64,{{ $chartImage }}" alt="Grafik Kehadiran Anggota" style="max-width: 100%; height: auto;">
        </div>

        <table class="summary-grid">
            <tr>
                <td>Total Anggota<strong>{{ $report['totals']['total_anggota'] }}</strong></td>
                <td>Total Kegiatan<strong>{{ $report['totals']['total_kegiatan'] }}</strong></td>
                <td>Total Record<strong>{{ $report['totals']['total_absensi'] }}</strong></td>
                <td>Attendance Rate<strong>{{ number_format($report['totals']['attendance_percentage'], 2) }}%</strong></td>
            </tr>
        </table>

        <h2>Ringkasan Per Anggota</h2>
        <table class="data">
            <thead>
                <tr>
                    <th>Nama</th>
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
                        <td>{{ $item['user']->name }}</td>
                        <td>{{ $item['user']->anggota?->npm ?? '-' }}</td>
                        <td>{{ $item['hadir'] }}</td>
                        <td>{{ $item['izin'] }}</td>
                        <td>{{ $item['alfa'] }}</td>
                        <td>{{ number_format($item['persentase'], 2) }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada data untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


        </table>
    </body>
</html>
