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
        if ($this->inputScheduleService->isScheduleSubmitted()) {
            $schedule = $this->inputScheduleService
                ->getSchedule()
                ->get();

            $totalWorkHour = $this->inputScheduleService
                ->calculateTotalWorkHour();

            $isUpdateSchedule = $this->inputScheduleService
                ->isUpdateSchedule();

            return view('attendance.input-schedule.index', [
                'schedule' => $schedule,
                'totalWorkHour' => $totalWorkHour,
                'isUpdateSchedule' => $isUpdateSchedule,
            ]);
        }

        return view('attendance.input-schedule.update');
    }

    public function update()
    {
        return view('attendance.input-schedule.update');
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
