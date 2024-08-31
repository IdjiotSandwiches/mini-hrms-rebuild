<?php

namespace App\Http\Controllers;

use App\Services\Admin\DashboardService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        $data = $dashboardService->getAttendanceCount();
        return view('admin.dashboard.index', with([
            'checkInOut' => $data->checkInOut,
            'attendances' => $data->attendances,
        ]));
    }
}
