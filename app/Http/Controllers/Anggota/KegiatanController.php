<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Services\AttendanceStatusService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request, AttendanceStatusService $attendanceStatusService): View
    {
        $search = trim((string) $request->string('search'));
        $user = $request->user();

        $kegiatan = Kegiatan::query()
            ->with([
                'absensis' => fn ($query) => $query->where('user_id', $user->id),
                'assignedUsers' => fn ($query) => $query->where('users.id', $user->id),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString()
            ->through(function (Kegiatan $kegiatan) use ($user, $attendanceStatusService) {
                $status = $attendanceStatusService->determineStatus($user, $kegiatan, $kegiatan->absensis->first());

                return [
                    'kegiatan' => $kegiatan,
                    'status' => $status,
                    'can_attend' => $status !== Absensi::STATUS_TIDAK_DITUGASKAN,
                ];
            });

        return view('anggota.kegiatan.index', [
            'kegiatan' => $kegiatan,
            'search' => $search,
        ]);
    }

    public function show(Kegiatan $kegiatan, Request $request, AttendanceStatusService $attendanceStatusService): View
    {
        $user = $request->user();

        $kegiatan->load([
            'absensis' => fn ($query) => $query->where('user_id', $user->id),
            'assignedUsers' => fn ($query) => $query->where('users.id', $user->id),
            'latestCode',
        ]);

        $attendance = $kegiatan->absensis->first();
        $status = $attendanceStatusService->determineStatus($user, $kegiatan, $attendance);

        return view('anggota.kegiatan.show', [
            'kegiatan' => $kegiatan,
            'status' => $status,
            'attendance' => $attendance,
            'canAttend' => $attendanceStatusService->canAttend($user, $kegiatan),
        ]);
    }
}
