<?php

namespace App\Http\Controllers;

use App\Services\Admin\DashboardService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        $dailyData = $dashboardService->getDailyAttendance();
        $weeklyData = $dashboardService->getWeeklyAttendance();
        dd($dashboardService->getMostOnTimeAndLate());
        return view('admin.dashboard.index', with([
            'daily' => $dailyData,
            'weekly' => $weeklyData,
        ]));
    }
}
