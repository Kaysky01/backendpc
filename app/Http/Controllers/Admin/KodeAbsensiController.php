<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KodeAbsensi;
use App\Services\ActivityLogService;
use App\Services\AttendanceCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KodeAbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->string('status');
        $selectedKegiatan = $request->integer('kegiatan_id');
        $kegiatanList = Kegiatan::query()
            ->with('latestCode')
            ->orderByDesc('tanggal')
            ->get();
        $currentKegiatan = $selectedKegiatan > 0
            ? $kegiatanList->firstWhere('id', $selectedKegiatan)
            : null;

        $kodeAbsensi = KodeAbsensi::query()
            ->with('kegiatan')
            ->when($selectedKegiatan > 0, fn ($query) => $query->where('kegiatan_id', $selectedKegiatan))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true)->where('expired_at', '>=', now()))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false)->where('expired_at', '>=', now()))
            ->when($status === 'expired', fn ($query) => $query->where('expired_at', '<', now()))
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.kode-absensi.index', [
            'kodeAbsensi' => $kodeAbsensi,
            'kegiatanList' => $kegiatanList,
            'currentKegiatan' => $currentKegiatan,
            'selectedKegiatan' => $selectedKegiatan,
            'status' => $status,
        ]);
    }

    public function store(
        Request $request,
        Kegiatan $kegiatan,
        AttendanceCodeService $attendanceCodeService,
        ActivityLogService $activityLogService
    ): RedirectResponse {
        $validated = $request->validate([
            'expired_option' => ['required', Rule::in(['10', '15', '30', 'custom'])],
            'expired_minutes_custom' => ['nullable', 'integer', 'min:1', 'max:1440'],
        ]);

        $expiredMinutes = $validated['expired_option'] === 'custom'
            ? (int) ($validated['expired_minutes_custom'] ?? 0)
            : (int) $validated['expired_option'];

        if ($expiredMinutes < 1) {
            return back()->with('error', 'Durasi expired custom harus diisi dalam menit.');
        }

        $kodeAbsensi = $attendanceCodeService->generate($kegiatan, $expiredMinutes);

        $activityLogService->log(
            $request->user(),
            'generate_kode',
            "Generate kode {$kodeAbsensi->kode} untuk kegiatan {$kegiatan->nama_kegiatan} dengan durasi {$expiredMinutes} menit."
        );

        return back()
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

    public function destroy(Request $request, KodeAbsensi $kodeAbsensi, ActivityLogService $activityLogService): RedirectResponse
    {
        $kode = $kodeAbsensi->kode;
        $namaKegiatan = $kodeAbsensi->kegiatan->nama_kegiatan;

        $kodeAbsensi->delete();

        $activityLogService->log(
            $request->user(),
            'delete_kode',
            "Menghapus kode {$kode} dari kegiatan {$namaKegiatan}."
        );

        return back()->with('success', "Kode absensi {$kode} berhasil dihapus.");
    }
}
