<?php

namespace App\Http\Controllers\V2;

use App\Enums\DayEnum;
use App\Exceptions\InputScheduleException;
use App\Http\Controllers\Controller;
use App\Http\Requests\InputScheduleRequest;
use App\Services\Attendances\InputScheduleService;
use Inertia\Inertia;

class InputScheduleController extends Controller
{
    private InputScheduleService $service;

    public function __construct(InputScheduleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return Inertia::render('attendances/InputSchedule', [
            'schedules' => $this->service->getSchedules(),
            'canUpdate' => $this->service->canUpdateSchedule(),
            'dayWeek' => DayEnum::columns(),
        ]);
    }

    public function store(InputScheduleRequest $request)
    {
        try {
            $data = $request->validated();
            $this->service->processSchedule($data);

            return Inertia::flash(
                'toast', ['type' => 'success', 'message' => __('Schedules updated.')]
            )->location(route('v2.input-schedule.index'));
        } catch (InputScheduleException $e) {
            return Inertia::flash(
                'toast', ['type' => 'warning', 'message' => __($e->getMessage())]
            )->back();
        } catch (\Exception $e) {
            return Inertia::flash(
                'toast', ['type' => 'error', 'message' => __('An error has occurred while saving the data. Please try again.')]
            )->back();
        }
    }
}
