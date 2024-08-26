<?php

namespace App\Http\Controllers;

use App\Http\Requests\InputScheduleRequest;
use App\Services\Attendance\InputScheduleService;

class InputScheduleController extends Controller
{
    public function index(InputScheduleService $inputScheduleService)
    {
        $schedule = $inputScheduleService->getSchedule()->get();
        $totalWorkHour = $inputScheduleService->calculateTotalWorkHour();
        $isUpdateSchedule = $inputScheduleService->isUpdateSchedule();
        $isScheduleSubmitted = $inputScheduleService->getSchedule()->exists();

        if ($isScheduleSubmitted) {
            return view('attendance.input-schedule.index', compact('schedule', 'totalWorkHour', 'isUpdateSchedule'));
        }

        return view('attendance.input-schedule.update', compact('isUpdateSchedule'));
    }

    public function update(InputScheduleService $inputScheduleService)
    {
        $isUpdateSchedule = $inputScheduleService->isUpdateSchedule();

        if ($isUpdateSchedule) return view('attendance.input-schedule.update', compact('isUpdateSchedule'));

        return redirect()->route('attendance.input-schedule-page')
            ->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
    }

    public function inputSchedule(InputScheduleRequest $request, InputScheduleService $inputScheduleService)
    {
        if (!$request->ajax()) abort(404);

        $validated = $request->validated();

        return $inputScheduleService->processSchedule($validated);
    }
}
