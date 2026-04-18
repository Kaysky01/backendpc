<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KodeAbsensi;
use App\Services\AttendanceCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KodeAbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->string('status');
        $selectedKegiatan = $request->integer('kegiatan_id');

        $kodeAbsensis = KodeAbsensi::query()
            ->with('kegiatan')
            ->when($selectedKegiatan > 0, fn ($query) => $query->where('kegiatan_id', $selectedKegiatan))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true)->where('expired_at', '>=', now()))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($status === 'expired', fn ($query) => $query->where('expired_at', '<', now()))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.kode-absensi.index', [
            'kodeAbsensis' => $kodeAbsensis,
            'kegiatanList' => Kegiatan::query()->with('latestCode')->orderByDesc('tanggal')->get(),
            'selectedKegiatan' => $selectedKegiatan,
            'status' => $status,
        ]);
    }

    public function store(Kegiatan $kegiatan, AttendanceCodeService $attendanceCodeService): RedirectResponse
    {
        $kodeAbsensi = $attendanceCodeService->generate($kegiatan);

        return redirect()
            ->route('admin.kode-absensi.index')
            ->with('success', "Kode absensi {$kodeAbsensi->kode} berhasil dibuat untuk kegiatan {$kegiatan->nama_kegiatan}.");
    }

    public function toggle(KodeAbsensi $kodeAbsensi): RedirectResponse
    {
        if (! $kodeAbsensi->is_active && $kodeAbsensi->isExpired()) {
            return back()->with('error', 'Kode yang sudah kedaluwarsa tidak bisa diaktifkan kembali.');
        }

        if (! $kodeAbsensi->is_active) {
            KodeAbsensi::query()
                ->where('kegiatan_id', $kodeAbsensi->kegiatan_id)
                ->where('id', '!=', $kodeAbsensi->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $kodeAbsensi->update([
            'is_active' => ! $kodeAbsensi->is_active,
        ]);

        return back()->with('success', 'Status kode absensi berhasil diperbarui.');
    }
}
