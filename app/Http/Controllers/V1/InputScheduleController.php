<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Exceptions\InputScheduleException;
use App\Http\Requests\InputScheduleRequest;
use App\Services\Attendances\InputScheduleService;

class InputScheduleController extends Controller
{
    private InputScheduleService $service;

    public function __construct(InputScheduleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $schedules = $this->service->getSchedules();
        $totalWorkHour = $this->service->calculateTotalWorkHour();
        $canUpdateSchedule = $this->service->canUpdateSchedule();
        $isScheduleSubmitted = $this->service->getSchedules()->isNotEmpty();

        if ($isScheduleSubmitted) {
            return view('attendance.input-schedule.index', compact('schedules', 'totalWorkHour', 'canUpdateSchedule'));
        }

        return view('attendance.input-schedule.update', compact('canUpdateSchedule'));
    }

    public function store(InputScheduleRequest $request)
    {
        if (!$request->ajax()) abort(404);

        try {
            $data = $request->validated();
            $this->service->processSchedule($data);

            return back()->with([
                'status' => 'success',
                'message' => 'Schedule has submitted successfully.',
            ]);
        } catch (InputScheduleException $e) {
            return back()->with([
                'status' => 'warning',
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }

    public function edit()
    {
        $canUpdateSchedule = $this->service->canUpdateSchedule();

        if ($canUpdateSchedule) return view('attendance.input-schedule.update', compact('canUpdateSchedule'));

        return redirect()->route('v1.input-schedule.index')
            ->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
    }
}
