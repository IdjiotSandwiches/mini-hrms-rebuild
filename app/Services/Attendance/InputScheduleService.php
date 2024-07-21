<?php

namespace App\Services\Attendance;

use App\Models\Schedule;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class InputScheduleService extends BaseService
{
    public function isScheduleSubmitted()
    {
        return $this->getSchedule()->exists();
    }

    public function getSchedule()
    {
        return Schedule::where('user_id', $this->getUser()->user_id);
    }

    public function calculateTotalWorkHour()
    {
        $schedule = $this->getSchedule();
        $totalWorkHour = $schedule->sum('work_time');
        $totalWorkHour = floor($totalWorkHour / 3600);
        return $totalWorkHour;
    }

    public function processSchedule($validated)
    {
        try {
            DB::beginTransaction();

            $totalWorkTime = 0;
            foreach($validated['schedule'] as $day => $value) {
                $validatedTime = $this->calculateWorkTime($value['start'], $value['end']);

                $totalWorkTime += $validatedTime->totalTime;

                Schedule::updateOrCreate(
                    ['user_id' => $this->getUser()->user_id, 'day' => $day],
                    [
                        'start_time' => $validatedTime->start,
                        'end_time' => $validatedTime->end,
                        'work_time' => $value['start'] == '00:00:00' && $value['end'] == '00:00:00' ? 0 : $validatedTime->totalTime,
                    ]
                );
            }

            if ($totalWorkTime < 20) {
                DB::rollBack();
                return back()->with([
                    'status' => 'error',
                    'message' => 'You must work at least 20 hours a week.',
                ]);
            }

            DB::commit();
            return back()->with([
                'status' => 'success',
                'message' => 'Schedule has submitted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.'
            ]);
        }
    }
}
