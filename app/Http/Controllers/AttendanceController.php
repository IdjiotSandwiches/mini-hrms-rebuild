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
        if ($this->isCheckedIn()) {
            return back()->withErrors([
                'alreadyCheckIn' => 'You have already checked in today.'
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
        if ($this->getAttendance()->exists()) return true;

        return false;
    }

    public function checkOut()
    {

    }
}
