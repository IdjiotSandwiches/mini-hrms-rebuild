<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\Factory;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function getCurrentTime()
    {
        $factoryTime = new Factory([
            'timezone' => 'Asia/Jakarta'
        ]);

        return $factoryTime->make(Carbon::now());
    }

    private function getUser()
    {
        return auth()->user();
    }

    private function getAttendance()
    {
        return Attendance::where('user_id', $this->getUser()->user_id)
        ->whereDate('date', $this->getCurrentTime()->toDateString());
    }

    public function takeAttendancePage()
    {
        return view('attendance.take-attendance.index', [
            'isCheckedIn' => $this->isCheckedIn()
        ]);
    }

    public function inputSchedulePage()
    {
        return view('attendance.input-schedule.index');
    }

    public function reportPage()
    {
        return view('attendance.report.index');
    }

    public function checkIn(Request $request)
    {
        if ($this->getAttendance()->exists()) {
            return back()->withErrors([
                'attendanceError' => 'You have already checked in today.'
            ]);
        }

        $attendance = new Attendance();
        $attendance->user_id = $this->getUser()->user_id;
        $attendance->check_in = $this->getCurrentTime();
        $attendance->date = $this->getCurrentTime()->toDateString();
        $attendance->save();

        return back()->with([
            'status' => 'success',
            'message' => 'Checked In',
        ]);
    }


    public function isCheckedIn()
    {
        $attendance = $this->getAttendance();
        if (!$attendance->exists()) return false;
        else {
            if (!$attendance->first()->check_out) return true;
            else if ($attendance->first()->check_out) return false;
        }
    }

    public function checkOut()
    {
        $checkInTime = $this->getAttendance()
            ->first()
            ->check_in;

        $currentTime = $this->getCurrentTime();
        $diffTime = $currentTime->diffInMinutes($checkInTime);

        if ($diffTime < 60) {
            return back()->withErrors([
                'attendanceError' => 'You need at least 1 hour to check out.'
            ]);
        }

        $this->getAttendance()
            ->first()
            ->update(['check_out' => $currentTime]);

        return back()->with([
            'status' => 'success',
            'message' => 'Checked Out',
        ]);
    }
}
