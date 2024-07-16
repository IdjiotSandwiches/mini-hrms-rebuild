<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\Factory;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function checkIn(Request $request)
    {
        $user = auth()->user();

        $factoryTime = new Factory([
            'timezone' => 'Asia/Jakarta'
        ]);
        $currentTime = $factoryTime->make(Carbon::now());

        if (Attendance::where('user_id', $user->user_id)
            ->whereDate('date', $currentTime->toDateString())
            ->exists()) {
                return back()->withErrors([
                    'alreadyCheckIn' => 'You have already checked in today.'
                ]);
            }

        $attendance = new Attendance();
        $attendance->user_id = $user->user_id;
        $attendance->check_in = $currentTime;
        $attendance->date = $currentTime->toDateString();
        $attendance->save();

        return redirect()->route('attendance.take-attendance-page')->with([
            'status' => 'success',
            'message' => 'Checked In',
        ]);
    }

    public function checkOut()
    {

    }
}
