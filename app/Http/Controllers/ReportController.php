<?php

namespace App\Http\Controllers;

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
        return view('attendance.report.index');
    }

    public function getWeeklyReport()
    {

    }
}
