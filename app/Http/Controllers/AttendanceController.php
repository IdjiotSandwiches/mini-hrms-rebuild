<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    public function takeAttendancePage()
    {
        return view('attendance.take-attendance.index');
    }

    public function inputSchedulePage()
    {
        return view('attendance.input-schedule.index');
    }

    public function reportPage()
    {
        return view('attendance.report.index');
    }
}
