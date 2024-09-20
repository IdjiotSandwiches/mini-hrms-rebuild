<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Attendance\TakeAttendanceService;

class TakeAttendanceController extends Controller
{
    public function index(TakeAttendanceService $takeAttendanceService)
    {
        return view('attendance.take-attendance.index', [
            'isCheckedIn' => $takeAttendanceService->isCheckedIn()
        ]);
    }

    public function takeAttendance(TakeAttendanceService $takeAttendanceService)
    {
        return $takeAttendanceService->attendanceValidation();
    }

    public function checkOut(TakeAttendanceService $takeAttendanceService)
    {
        return $takeAttendanceService->checkOutValidation();
    }
}
