<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Services\AttendanceAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, AttendanceAnalyticsService $analytics): View
    {
        return view('anggota.dashboard', [
            'stats' => $analytics->memberStats($request->user()),
            'upcomingKegiatan' => Kegiatan::query()
                ->whereDate('tanggal', '>=', now()->toDateString())
                ->orderBy('tanggal')
                ->take(5)
                ->get(),
        ]);
    }
}
