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
        return $this->takeAttendanceService->checkInValidation();
    }

    public function checkOut()
    {
        return $this->takeAttendanceService->checkOutValidation();
    }
}
