<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\KodeAbsensi;
use App\Services\AttendanceStatusService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceCodeController extends Controller
{
    public function create(Request $request): View
    {
        return view('anggota.absensi.create', [
            'prefilledCode' => strtoupper(trim((string) $request->string('kode'))),
        ]);
    }

    public function store(Request $request, AttendanceStatusService $attendanceStatusService): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'size:6', 'regex:/^[A-Za-z0-9]{6}$/'],
        ]);

        $kodeInput = strtoupper(trim($validated['kode']));
        $kodeAbsensi = KodeAbsensi::query()
            ->with('kegiatan')
            ->where('kode', $kodeInput)
            ->first();

        if (! $kodeAbsensi) {
            return back()->with('error', 'Kode tidak valid')->withInput();
        }

        if (! $kodeAbsensi->is_active) {
            return back()->with('error', 'Kode tidak valid')->withInput();
        }

        if ($kodeAbsensi->isExpired()) {
            return back()->with('error', 'Kode expired')->withInput();
        }

        $user = $request->user();

        if (! $attendanceStatusService->canAttend($user, $kodeAbsensi->kegiatan->loadMissing('assignedUsers'))) {
            return back()->with('error', 'Tidak ditugaskan')->withInput();
        }

        $alreadyExists = Absensi::query()
            ->where('user_id', $user->id)
            ->where('kegiatan_id', $kodeAbsensi->kegiatan_id)
            ->exists();

        if ($alreadyExists) {
            return back()->with('error', 'Sudah absen')->withInput();
        }

        Absensi::query()->create([
            'user_id' => $user->id,
            'kegiatan_id' => $kodeAbsensi->kegiatan_id,
            'status' => Absensi::STATUS_HADIR,
            'waktu_absen' => now(),
        ]);

        return redirect()
            ->route('anggota.riwayat.index')
            ->with('success', 'Absensi berhasil');
    }
}
