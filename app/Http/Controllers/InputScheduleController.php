<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Attendance\InputScheduleService;

class InputScheduleController extends Controller
{
    private $inputScheduleService;

    public function __construct()
    {
        $this->inputScheduleService = new InputScheduleService();
    }

    public function index()
    {
        $isScheduleSubmitted = $this->inputScheduleService
            ->isScheduleSubmitted();
        $schedule = $this->inputScheduleService
            ->getSchedule()
            ->get() ?? null;
        $totalWorkHour = $this->inputScheduleService
            ->calculateTotalWorkHour() ?? null;

        return view('attendance.input-schedule.index', [
            'isScheduleSubmitted' => $isScheduleSubmitted,
            'schedule' => $schedule,
            'totalWorkHour' => $totalWorkHour,
        ]);
    }

    public function inputSchedule(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $validated = $request->validate([
            'schedule' => 'required|array'
        ]);

        return $this->inputScheduleService->processSchedule($validated);
    }
}
