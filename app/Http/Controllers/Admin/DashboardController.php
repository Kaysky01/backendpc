<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AttendanceAnalyticsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(AttendanceAnalyticsService $analytics): View
    {
        return view('admin.dashboard', $analytics->adminDashboardData());
    }
}
