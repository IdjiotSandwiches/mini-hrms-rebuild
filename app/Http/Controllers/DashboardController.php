<?php

namespace App\Http\Controllers;

use App\Services\Admin\DashboardService;
use App\Http\Controllers\Controller;

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
