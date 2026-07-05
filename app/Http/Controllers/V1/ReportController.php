<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Attendances\ReportService;

class ReportController extends Controller
{
    private ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
        ]);

        $startTime = $validated['start_time'] ?? null;
        $endTime = $validated['end_time'] ?? null;

        $weeklyAttendances = $this->service->getWeeklyReport();
        $monthlyAttendances = $this->service->getMonthlyReport();
        $rangedAttendances = $this->service->getRangedReport($startTime, $endTime);

        return view('attendance.report.index', [
            'weeklyAttendances' => $weeklyAttendances['attendances'],
            'weeklyWorkHours' => $weeklyAttendances['hours'],
            'monthlyAttendances' => $monthlyAttendances['attendances'],
            'monthlyWorkHours' => $monthlyAttendances['hours'],
            'rangedAttendances' => $rangedAttendances['attendances'],
            'rangedWorkHours' => $rangedAttendances['hours'],
        ]);
    }
}
