<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Attendance\ReportService;

class ReportController extends Controller
{
    private $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
    }

    public function index()
    {
        $weeklyAttendances = $this->reportService
            ->getWeeklyReport();
        $monthlyAttendances = $this->reportService
            ->getMonthlyReport();

        return view('attendance.report.index', [
            'weeklyAttendances' => $weeklyAttendances->attendances,
            'weeklyWorkHours' => $weeklyAttendances->workHours,
            'monthlyAttendances' => $monthlyAttendances->attendances,
            'monthlyWorkHours' => $monthlyAttendances->workHours,
        ]);
    }

    public function rangeReport(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $validated = $request->validated();

        $startTime = $validated->startTime;
        $endTime = $validated->endTime;

        return $this->reportService->getRangeReport($startTime, $endTime);
    }
}
