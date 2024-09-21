<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReportRequest;
use App\Interfaces\ScheduleInterface;
use App\Services\Attendance\ReportService;

class ReportController extends Controller implements
    ScheduleInterface
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

        $startTime = $validated[self::START_TIME_COLUMN];
        $endTime = $validated[self::END_TIME_COLUMN];

        return $reportService->getRangeReport($startTime, $endTime);
    }
}
