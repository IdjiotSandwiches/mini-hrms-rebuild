<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
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
        if ($this->takeAttendanceService
            ->getAttendance()
            ->exists()) {
                return back()->withErrors([
                    'attendanceError' => 'You have already checked in today.'
                ]);
        }

        $attendance = new Attendance();
        $attendance->user_id = $this->takeAttendanceService
            ->getUser()
            ->user_id;
        $attendance->check_in = $this->takeAttendanceService
            ->getCurrentTime();
        $attendance->date = $this->takeAttendanceService
            ->getCurrentTime()
            ->toDateString();
        $attendance->save();

        return back()->with([
            'status' => 'success',
            'message' => 'Checked In',
        ]);
    }

    public function checkOut()
    {
        $checkInTime = $this->takeAttendanceService
            ->getAttendance()
            ->first()
            ->check_in;

        $currentTime = $this->takeAttendanceService
            ->getCurrentTime();
        $diffTime = $currentTime->diffInMinutes($checkInTime);

        if ($diffTime < 60) {
            return back()->withErrors([
                'attendanceError' => 'You need at least 1 hour to check out.'
            ]);
        }

        $this->takeAttendanceService
            ->getAttendance()
            ->first()
            ->update(['check_out' => $currentTime]);

        return back()->with([
            'status' => 'success',
            'message' => 'Checked Out',
        ]);
    }
}
