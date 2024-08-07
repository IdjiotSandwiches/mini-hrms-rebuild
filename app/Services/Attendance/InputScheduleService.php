<?php

namespace App\Services\Attendance;

use Carbon\Carbon;
use App\Models\Schedule;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class InputScheduleService extends BaseService
{
    public function isUpdateSchedule() {
        if (!$this->getSchedule()->exists()) return false;

        $currentTime = $this->convertTime(Carbon::now());
        $schedule = $this->getSchedule()->first();
        $lastScheduleUpdate = $this->convertTime($schedule->updated_at);

        if ($currentTime->diffInMonths($lastScheduleUpdate) >= 3) return true;

        return false;
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
                    [
                        'user_id' => $this->getUser()->user_id,
                        'day' => $day
                    ],
                    [
                        'start_time' => $validatedTime->start,
                        'end_time' => $validatedTime->end,
                        'work_time' => $value['start'] == '00:00:00' && $value['end'] == '00:00:00' ? 0 : $validatedTime->totalTime,
                    ]
                );
            }

            if ($totalWorkTime < 20) {
                DB::rollBack();
                $response = [
                    'status' => 'error',
                    'message' => 'You must work at least 20 hours a week.',
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => 'error',
                'message' => 'Invalid operation.',
            ];

            return $response;
        }

        $response = [
            'status' => 'success',
            'message' => 'Schedule has submitted successfully.',
        ];

        return $response;
    }
}
