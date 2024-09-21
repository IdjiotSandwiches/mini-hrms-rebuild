<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReportRequest;
use App\Services\Attendance\ReportService;

class ReportController extends Controller
{
    public function index(ReportService $reportService)
    {
        $weeklyAttendances = $reportService->getWeeklyReport();
        $monthlyAttendances = $reportService->getMonthlyReport();

        return view('attendance.report.index', [
            'weeklyAttendances' => $weeklyAttendances->attendances,
            'weeklyWorkHours' => $weeklyAttendances->workHours,
            'monthlyAttendances' => $monthlyAttendances->attendances,
            'monthlyWorkHours' => $monthlyAttendances->workHours,
        ]);
    }

    public function rangeReport(ReportRequest $request, ReportService $reportService)
    {
        if (!$request->ajax()) abort(404);

        $validated = $request->validated();

        $startTime = $validated['start_time'];
        $endTime = $validated['end_time'];

        return $reportService->getRangeReport($startTime, $endTime);
    }
}
