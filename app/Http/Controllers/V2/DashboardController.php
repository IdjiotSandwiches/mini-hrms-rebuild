<?php

namespace App\Http\Controllers\V2;

use Inertia\Inertia;
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
        return Inertia::render('admin/Dashboard', [
            'daily'     => $this->service->getDailyAttendance(),
            'weekly'    => $this->service->getWeeklyAttendance(),
            'rank'      => $this->service->getMostOnTimeAndAbsence()
        ]);
    }
}
