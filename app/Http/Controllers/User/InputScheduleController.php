<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\InputScheduleRequest;
use App\Interfaces\StatusInterface;
use App\Services\Attendance\InputScheduleService;

class InputScheduleController extends Controller implements
    StatusInterface
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
                'status' => self::STATUS_ERROR,
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
