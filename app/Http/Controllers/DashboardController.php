<?php

namespace App\Http\Controllers;

use App\Services\Admin\DashboardService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function getData(Request $request, DashboardService $dashboardService)
    {
        if (!$request->ajax()) abort(404);

        $attendanceCount = $dashboardService->getAttendanceCount();

        return $attendanceCount;
    }
}
