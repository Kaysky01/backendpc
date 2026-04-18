<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\KodeAbsensi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceCodeController extends Controller
{
    public function create(): View
    {
        return view('anggota.absensi.create');
    }

    public function store(Request $request): RedirectResponse
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
            return back()->withErrors(['kode' => 'Kode absensi tidak ditemukan.'])->withInput();
        }

        if (! $kodeAbsensi->is_active) {
            return back()->withErrors(['kode' => 'Kode absensi ini sedang tidak aktif.'])->withInput();
        }

        if ($kodeAbsensi->isExpired()) {
            return back()->withErrors(['kode' => 'Kode absensi sudah kedaluwarsa.'])->withInput();
        }

        $user = $request->user();
        $alreadyExists = Absensi::query()
            ->where('user_id', $user->id)
            ->where('kegiatan_id', $kodeAbsensi->kegiatan_id)
            ->exists();

        if ($alreadyExists) {
            return back()->withErrors(['kode' => 'Anda sudah melakukan absensi untuk kegiatan ini.'])->withInput();
        }

        Absensi::query()->create([
            'user_id' => $user->id,
            'kegiatan_id' => $kodeAbsensi->kegiatan_id,
            'status' => Absensi::STATUS_HADIR,
            'waktu_absen' => now(),
        ]);

        return redirect()
            ->route('anggota.riwayat.index')
            ->with('success', "Absensi untuk kegiatan {$kodeAbsensi->kegiatan->nama_kegiatan} berhasil disimpan.");
    }
}
