<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Services\AttendanceAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class AttendanceHistoryController extends Controller
{
    public function index(Request $request, AttendanceAnalyticsService $analytics): View
    {
        $validated = $request->validate([
            'period' => ['nullable', 'integer', Rule::in([1, 3, 6])],
        ]);

        $period = (int) ($validated['period'] ?? 1);
        $fromDate = now()->subMonths($period)->startOfDay();
        $user = $request->user();

        $history = Kegiatan::query()
            ->whereDate('tanggal', '>=', $fromDate->toDateString())
            ->with(['absensis' => fn ($query) => $query->where('user_id', $user->id)])
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString()
            ->through(function (Kegiatan $kegiatan) {
                $attendance = $kegiatan->absensis->first();
                $status = $attendance?->status;

                if (! $status) {
                    $status = $kegiatan->tanggal->startOfDay()->lte(now()->startOfDay())
                        ? 'alfa'
                        : 'belum absen';
                }

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
