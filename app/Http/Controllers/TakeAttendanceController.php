<?php

namespace App\Http\Controllers;

use App\Services\Attendance\TakeAttendanceService;
use Illuminate\Http\Request;

class TakeAttendanceController extends Controller
{
    private $takeAttendanceService;

    public function __construct() {
        $this->takeAttendanceService = new TakeAttendanceService();
    }

    public function index()
    {
        return view('attendance.take-attendance.index', [
            'isCheckedIn' => $this->takeAttendanceService->isCheckedIn()
        ]);
    }

    public function checkIn(Request $request)
    {
        if (!$this->takeAttendanceService
            ->getSchedule()
            ->exists()) {
                return back()->withErrors([
                    'attendanceError' => 'Input schedule first.'
                ]);
        }

        if ($this->takeAttendanceService
            ->isCheckedIn()) {
                return back()->withErrors([
                    'attendanceError' => 'You have already checked in today.'
                ]);
        }

        return $this->takeAttendanceService->checkInValidation();
    }

    public function checkOut()
    {
        return $this->takeAttendanceService->checkOutValidation();
    }
}
