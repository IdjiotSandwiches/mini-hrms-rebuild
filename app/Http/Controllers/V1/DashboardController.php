<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;

class DashboardController extends Controller
{
    private DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $dailyData = $this->service->getDailyAttendance();
        $weeklyData = $this->service->getWeeklyAttendance();
        $mostRank = $this->service->getMostOnTimeAndAbsence();

        return view('admin.dashboard.index', with([
            'daily' => $dailyData,
            'weekly' => $weeklyData,
            'mostRank' => $mostRank,
        ]));
    }
}
