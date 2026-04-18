<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceAnalyticsService
{
    /**
     * @return array{
     *     total_anggota:int,
     *     total_kegiatan:int,
     *     total_absensi:int,
     *     attendance_percentage:float,
     *     chart_labels:array<int, string>,
     *     chart_values:array<int, int>,
     *     recent_absensi:\Illuminate\Support\Collection<int, \App\Models\Absensi>
     * }
     */
    public function adminDashboardData(int $months = 6): array
    {
        $chartData = $this->monthlyAttendanceChart($months);

        return [
            'total_anggota' => User::query()->where('role', User::ROLE_ANGGOTA)->count(),
            'total_kegiatan' => Kegiatan::query()->count(),
            'total_absensi' => Absensi::query()->count(),
            'attendance_percentage' => $this->attendancePercentage(),
            'chart_labels' => $chartData->pluck('label')->all(),
            'chart_values' => $chartData->pluck('total')->all(),
            'recent_absensi' => Absensi::query()
                ->with(['user.anggota', 'kegiatan'])
                ->latest('waktu_absen')
                ->take(6)
                ->get(),
        ];
    }

    /**
     * @return array{
     *     hadir:int,
     *     izin:int,
     *     alfa:int,
     *     total_kegiatan:int,
     *     total_kegiatan_lalu:int
     * }
     */
    public function memberStats(User $user, ?Carbon $fromDate = null): array
    {
        $kegiatan = Kegiatan::query()
            ->when($fromDate, fn ($query) => $query->whereDate('tanggal', '>=', $fromDate->toDateString()))
            ->orderBy('tanggal')
            ->get(['id', 'tanggal']);

        $records = Absensi::query()
            ->where('user_id', $user->id)
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereHas('kegiatan', function ($kegiatanQuery) use ($fromDate) {
                    $kegiatanQuery->whereDate('tanggal', '>=', $fromDate->toDateString());
                });
            })
            ->get(['kegiatan_id', 'status']);

        $recordsByKegiatan = $records->keyBy('kegiatan_id');
        $today = now()->startOfDay();
        $recordedAlfa = $records->where('status', Absensi::STATUS_ALFA)->count();
        $missingAlfa = $kegiatan
            ->filter(fn (Kegiatan $item) => $item->tanggal->startOfDay()->lte($today) && ! $recordsByKegiatan->has($item->id))
            ->count();

        return [
            'hadir' => $records->where('status', Absensi::STATUS_HADIR)->count(),
            'izin' => $records->where('status', Absensi::STATUS_IZIN)->count(),
            'alfa' => $recordedAlfa + $missingAlfa,
            'total_kegiatan' => $kegiatan->count(),
            'total_kegiatan_lalu' => $kegiatan->filter(fn (Kegiatan $item) => $item->tanggal->startOfDay()->lte($today))->count(),
        ];
    }

    /**
     * @return array{
     *     months:int,
     *     from_date:\Carbon\Carbon,
     *     kegiatan:\Illuminate\Support\Collection<int, \App\Models\Kegiatan>,
     *     records:\Illuminate\Support\Collection<int, \App\Models\Absensi>,
     *     summary:\Illuminate\Support\Collection<int, array<string, mixed>>,
     *     totals:array<string, int|float>
     * }
     */
    public function reportData(int $months): array
    {
        $fromDate = now()->subMonths($months)->startOfDay();
        $kegiatan = Kegiatan::query()
            ->whereDate('tanggal', '>=', $fromDate->toDateString())
            ->orderByDesc('tanggal')
            ->get();

        $anggota = User::query()
            ->where('role', User::ROLE_ANGGOTA)
            ->with('anggota')
            ->orderBy('name')
            ->get();

        $records = Absensi::query()
            ->with(['user.anggota', 'kegiatan'])
            ->whereIn('kegiatan_id', $kegiatan->pluck('id'))
            ->orderByDesc('waktu_absen')
            ->get();

        $recordsByUser = $records->groupBy('user_id');
        $today = now()->startOfDay();

        $summary = $anggota->map(function (User $user) use ($recordsByUser, $kegiatan, $today) {
            $userRecords = $recordsByUser->get($user->id, collect())->keyBy('kegiatan_id');
            $hadir = $userRecords->where('status', Absensi::STATUS_HADIR)->count();
            $izin = $userRecords->where('status', Absensi::STATUS_IZIN)->count();
            $recordedAlfa = $userRecords->where('status', Absensi::STATUS_ALFA)->count();
            $missingAlfa = $kegiatan
                ->filter(fn (Kegiatan $item) => $item->tanggal->startOfDay()->lte($today) && ! $userRecords->has($item->id))
                ->count();
            $alfa = $recordedAlfa + $missingAlfa;
            $totalKegiatan = $kegiatan->count();

            return [
                'user' => $user,
                'hadir' => $hadir,
                'izin' => $izin,
                'alfa' => $alfa,
                'persentase' => $totalKegiatan > 0 ? round(($hadir / $totalKegiatan) * 100, 2) : 0.0,
            ];
        });

        return [
            'months' => $months,
            'from_date' => $fromDate,
            'kegiatan' => $kegiatan,
            'records' => $records,
            'summary' => $summary,
            'totals' => [
                'total_anggota' => $anggota->count(),
                'total_kegiatan' => $kegiatan->count(),
                'total_absensi' => $records->count(),
                'hadir' => $records->where('status', Absensi::STATUS_HADIR)->count(),
                'izin' => $records->where('status', Absensi::STATUS_IZIN)->count(),
                'alfa' => $summary->sum('alfa'),
                'attendance_percentage' => $this->attendancePercentage($fromDate),
            ],
        ];
    }

    public function attendancePercentage(?Carbon $fromDate = null): float
    {
        $totalAnggota = User::query()->where('role', User::ROLE_ANGGOTA)->count();
        $totalKegiatan = Kegiatan::query()
            ->when($fromDate, fn ($query) => $query->whereDate('tanggal', '>=', $fromDate->toDateString()))
            ->count();

        if ($totalAnggota === 0 || $totalKegiatan === 0) {
            return 0.0;
        }

        $hadirCount = Absensi::query()
            ->where('status', Absensi::STATUS_HADIR)
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereHas('kegiatan', function ($kegiatanQuery) use ($fromDate) {
                    $kegiatanQuery->whereDate('tanggal', '>=', $fromDate->toDateString());
                });
            })
            ->count();

        return round(($hadirCount / ($totalAnggota * $totalKegiatan)) * 100, 2);
    }

    /**
     * @return Collection<int, array{label:string,total:int}>
     */
    private function monthlyAttendanceChart(int $months): Collection
    {
        $startMonth = now()->startOfMonth()->subMonths($months - 1);
        $records = Absensi::query()
            ->where('status', Absensi::STATUS_HADIR)
            ->where('waktu_absen', '>=', $startMonth)
            ->get(['waktu_absen']);

        $grouped = $records->groupBy(fn (Absensi $item) => $item->waktu_absen->format('Y-m'));

        return collect(range(0, $months - 1))->map(function (int $offset) use ($grouped, $startMonth) {
            $month = $startMonth->copy()->addMonths($offset);
            $key = $month->format('Y-m');

            return [
                'label' => $month->format('M Y'),
                'total' => $grouped->get($key, collect())->count(),
            ];
        });
    }
}
