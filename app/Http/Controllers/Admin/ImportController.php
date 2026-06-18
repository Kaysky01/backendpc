<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AttendanceImport;
use App\Imports\UserImport;
use App\Models\Absensi;
use App\Models\ImportHistory;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importAnggota(Request $request, ActivityLogService $activityLogService): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $import = new UserImport;
        Excel::import($import, $validated['file']);

        $activityLogService->log(
            $request->user(),
            'import_data',
            "Import anggota: {$import->imported} data diproses, {$import->skipped} data dilewati."
        );

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', "Import anggota selesai. Total imported: {$import->imported}. Total skipped: {$import->skipped}.");
    }

    public function importAbsensi(Request $request, ActivityLogService $activityLogService): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        // Snapshot ID absensi yang sudah ada sebelum import
        $beforeIds = Absensi::pluck('id')->toArray();

        $import = new AttendanceImport;
        Excel::import($import, $validated['file']);

        // Hitung ID baru yang dibuat oleh import ini
        $afterIds = Absensi::pluck('id')->toArray();
        $newIds = array_values(array_diff($afterIds, $beforeIds));

        // Simpan riwayat import
        ImportHistory::query()->create([
            'user_id' => $request->user()->id,
            'file_name' => $validated['file']->getClientOriginalName(),
            'imported_count' => $import->imported,
            'skipped_count' => $import->skipped,
            'record_ids' => $newIds,
        ]);

        $activityLogService->log(
            $request->user(),
            'import_data',
            "Import absensi: {$import->imported} data diproses, {$import->skipped} data dilewati."
        );

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', "Import absensi selesai. Total imported: {$import->imported}. Total skipped: {$import->skipped}.");
    }

    public function rollback(ImportHistory $importHistory, ActivityLogService $activityLogService): RedirectResponse
    {
        $deletedCount = 0;

        if ($importHistory->record_ids && count($importHistory->record_ids) > 0) {
            $deletedCount = Absensi::whereIn('id', $importHistory->record_ids)->delete();
        }

        $activityLogService->log(
            request()->user(),
            'rollback_import',
            "Rollback import \"{$importHistory->file_name}\": {$deletedCount} data absensi dihapus."
        );

        $importHistory->delete();

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', "Rollback berhasil. {$deletedCount} data absensi telah dihapus.");
    }
}
