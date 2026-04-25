<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Services\AttendanceAnalyticsService;
use App\Services\AttendanceStatusService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceHistoryController extends Controller
{
    public function index(
        Request $request,
        AttendanceAnalyticsService $analytics,
        AttendanceStatusService $attendanceStatusService
    ): View {
        $validated = $request->validate([
            'period' => ['nullable', 'integer', Rule::in([1, 3, 6])],
        ]);

        $period = (int) ($validated['period'] ?? 1);
        $fromDate = now()->subMonths($period)->startOfDay();
        $user = $request->user();

        $history = Kegiatan::query()
            ->whereDate('tanggal', '>=', $fromDate->toDateString())
            ->with([
                'absensis' => fn ($query) => $query->where('user_id', $user->id),
                'assignedUsers' => fn ($query) => $query->where('users.id', $user->id),
            ])
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString()
            ->through(function (Kegiatan $kegiatan) use ($user, $attendanceStatusService) {
                $attendance = $kegiatan->absensis->first();
                $status = $attendanceStatusService->determineStatus($user, $kegiatan, $attendance);

                return [
                    'kegiatan' => $kegiatan,
                    'status' => $status,
                    'waktu_absen' => $attendance?->waktu_absen,
                ];
            });

        return view('anggota.riwayat.index', [
            'period' => $period,
            'history' => $history,
            'stats' => $analytics->memberStats($user, $fromDate),
        ]);
    }
}
