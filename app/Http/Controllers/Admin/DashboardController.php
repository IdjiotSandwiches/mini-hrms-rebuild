<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        $dailyData = $dashboardService->getDailyAttendance();
        $weeklyData = $dashboardService->getWeeklyAttendance();
        $mostRank = $dashboardService->getMostOnTimeAndAbsence();

        return view('admin.dashboard.index', with([
            'daily' => $dailyData,
            'weekly' => $weeklyData,
            'mostRank' => $mostRank,
        ]));
    }
}
