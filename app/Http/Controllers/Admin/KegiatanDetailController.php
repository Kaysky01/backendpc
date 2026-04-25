<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\User;
use App\Services\AttendanceStatusService;
use Illuminate\Contracts\View\View;

class KegiatanDetailController extends Controller
{
    public function show(Kegiatan $kegiatan, AttendanceStatusService $attendanceStatusService): View
    {
        $kegiatan->load([
            'assignedUsers.anggota',
            'absensis.user.anggota',
            'latestCode',
        ]);

        $allAnggota = User::query()
            ->where('role', User::ROLE_ANGGOTA)
            ->with('anggota')
            ->orderBy('name')
            ->get();

        $assignedUsers = $kegiatan->assignedUsers->sortBy('name')->values();
        $attendanceByUser = $kegiatan->absensis->keyBy('user_id');

        $assignedMembers = $assignedUsers->map(function (User $user) use ($kegiatan, $attendanceByUser, $attendanceStatusService) {
            return [
                'user' => $user,
                'status' => $attendanceStatusService->determineStatus($user, $kegiatan, $attendanceByUser->get($user->id)),
                'waktu_absen' => $attendanceByUser->get($user->id)?->waktu_absen,
            ];
        });

        $hadirMembers = $assignedMembers->filter(fn (array $item) => $item['status'] === Absensi::STATUS_HADIR)->values();
        $alfaMembers = $assignedMembers->filter(fn (array $item) => $item['status'] === Absensi::STATUS_ALFA)->values();
        $tidakDitugaskanMembers = $allAnggota
            ->reject(fn (User $user) => $assignedUsers->contains('id', $user->id))
            ->values();

        return view('admin.kegiatan.show', [
            'kegiatan' => $kegiatan,
            'assignedMembers' => $assignedMembers,
            'hadirMembers' => $hadirMembers,
            'alfaMembers' => $alfaMembers,
            'tidakDitugaskanMembers' => $tidakDitugaskanMembers,
            'stats' => [
                'total_ditugaskan' => $assignedMembers->count(),
                'total_hadir' => $hadirMembers->count(),
                'total_alfa' => $alfaMembers->count(),
                'total_tidak_ditugaskan' => $tidakDitugaskanMembers->count(),
            ],
        ]);
    }
}
