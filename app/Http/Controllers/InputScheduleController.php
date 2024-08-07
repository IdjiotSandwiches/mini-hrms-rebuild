<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputScheduleRequest;
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
        $schedule = $this->inputScheduleService
            ->getSchedule()
            ->get();

        $totalWorkHour = $this->inputScheduleService
            ->calculateTotalWorkHour();

        $isUpdateSchedule = $this->inputScheduleService
            ->isUpdateSchedule();

        $isScheduleSubmitted = $this->inputScheduleService
            ->getSchedule()
            ->exists();

        if ($isScheduleSubmitted) {
            return view('attendance.input-schedule.index', compact('schedule', 'totalWorkHour', 'isUpdateSchedule'));
        }

        return view('attendance.input-schedule.update', compact('isUpdateSchedule'));
    }

    public function update()
    {
        $isUpdateSchedule = $this->inputScheduleService
            ->isUpdateSchedule();

        if ($isUpdateSchedule) return view('attendance.input-schedule.update', compact('isUpdateSchedule'));

        return redirect()->route('attendance.input-schedule-page')
            ->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
    }

    public function inputSchedule(InputScheduleRequest $request)
    {
        if (!$request->ajax()) abort(404);

        $validated = $request->validated();

        return $this->inputScheduleService->processSchedule($validated);
    }
}
