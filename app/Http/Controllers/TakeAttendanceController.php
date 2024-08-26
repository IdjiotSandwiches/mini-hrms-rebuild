<?php

namespace App\Http\Controllers;

use App\Services\Attendance\TakeAttendanceService;
use Illuminate\Http\Request;

class TakeAttendanceController extends Controller
{
    public function index(TakeAttendanceService $takeAttendanceService)
    {
        return view('attendance.take-attendance.index', [
            'isCheckedIn' => $takeAttendanceService->isCheckedIn()
        ]);
    }

    public function checkIn(TakeAttendanceService $takeAttendanceService)
    {
        return $takeAttendanceService->checkInValidation();
    }

    public function checkOut(TakeAttendanceService $takeAttendanceService)
    {
        return $takeAttendanceService->checkOutValidation();
    }
}
