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
use Illuminate\Support\Facades\DB;

class KodeAbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $status = (string) $request->string('status');
        $selectedKegiatan = $request->integer('kegiatan_id');

        $kodeAbsensi = KodeAbsensi::query()
    ->with('kegiatan')
    ->select('kode_absensi.*')
    ->join(
        DB::raw('(SELECT MAX(id) as id FROM kode_absensi GROUP BY kegiatan_id) as latest'),
        'kode_absensi.id',
        '=',
        'latest.id'
    )
    ->when($selectedKegiatan > 0, fn ($query) => $query->where('kode_absensi.kegiatan_id', $selectedKegiatan))
    ->when($status === 'active', fn ($query) => $query->where('is_active', true)->where('expired_at', '>=', now()))
    ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
    ->when($status === 'expired', fn ($query) => $query->where('expired_at', '<', now()))
    ->latest('kode_absensi.id')
    ->paginate(12)
    ->withQueryString();

        return view('admin.kode-absensi.index', [
            'kodeAbsensi' => $kodeAbsensi,
            'kegiatanList' => Kegiatan::query()->with('latestCode')->orderByDesc('tanggal')->get(),
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
