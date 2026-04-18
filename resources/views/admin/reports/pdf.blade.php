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

        <h2>Daftar Absensi Tercatat</h2>
        <table class="data">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kegiatan</th>
                    <th>Status</th>
                    <th>Waktu Absen</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($report['records'] as $item)
                    <tr>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->kegiatan->nama_kegiatan }} ({{ $item->kegiatan->tanggal->format('d M Y') }})</td>
                        <td>{{ strtoupper($item->status) }}</td>
                        <td>{{ $item->waktu_absen->format('d M Y H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Belum ada record absensi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
