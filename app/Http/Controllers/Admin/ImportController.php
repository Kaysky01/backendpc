<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AttendanceImport;
use App\Imports\UserImport;
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

        $import = new AttendanceImport;
        Excel::import($import, $validated['file']);

        $activityLogService->log(
            $request->user(),
            'import_data',
            "Import absensi: {$import->imported} data diproses, {$import->skipped} data dilewati."
        );

        return redirect()
            ->route('admin.absensi.index')
            ->with('success', "Import absensi selesai. Total imported: {$import->imported}. Total skipped: {$import->skipped}.");
    }
}
