<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AttendanceAnalyticsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(Request $request, AttendanceAnalyticsService $analytics): View
    {
        $months = $this->resolvePeriod($request);

        return view('admin.reports.index', [
            'period' => $months,
            'report' => $analytics->reportData($months),
        ]);
    }

    public function export(Request $request, AttendanceAnalyticsService $analytics)
    {
        $months = $this->resolvePeriod($request);
        $report = $analytics->reportData($months);

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'period' => $months,
            'report' => $report,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download("rekap-absensi-{$months}-bulan.pdf");
    }

    private function resolvePeriod(Request $request): int
    {
        $validated = $request->validate([
            'period' => ['nullable', 'integer', Rule::in([1, 3, 6])],
        ]);

        return (int) ($validated['period'] ?? 1);
    }
}
