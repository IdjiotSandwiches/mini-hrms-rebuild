<?php

namespace App\Services\Attendance;

use App\Interfaces\ScheduleInterface;
use App\Interfaces\StatusInterface;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class InputScheduleService extends BaseService implements
    ScheduleInterface,
    StatusInterface
{
    /**
     * @return bool
     */
    public function isUpdateSchedule() {
        if (!$this->getSchedule()->exists()) return false;

        $currentTime = $this->convertTime(Carbon::now());
        $schedule = $this->getSchedule()->first();
        $lastScheduleUpdate = $this->convertTime($schedule->updated_at);

        if ($currentTime->diffInMonths($lastScheduleUpdate) >= 3) return true;

        return false;
    }

    /**
     * @return float
     */
    public function calculateTotalWorkHour()
    {
        $schedule = $this->getSchedule();
        $totalWorkHour = $schedule->sum(self::WORK_TIME_COLUMN);
        $totalWorkHour = floor($totalWorkHour / 3600);
        return $totalWorkHour;
    }

    /**
     * @param array
     * @return array
     */
    public function processSchedule($validated)
    {
        try {
            DB::beginTransaction();

            $totalWorkTime = 0;
            foreach($validated['schedule'] as $day => $value) {
                $validatedTime = $this->calculateWorkTime($value['start'], $value['end']);

                $totalWorkTime += $validatedTime->totalTime;

                $schedule = Schedule::firstOrNew([
                    'user_id' => $this->getUser()->id,
                    'day' => $day
                ]);

                $schedule->start_time = $validatedTime->start;
                $schedule->end_time = $validatedTime->end;
                $schedule->work_time = $value['start'] == '00:00:00' && $value['end'] == '00:00:00' ? 0 : $validatedTime->totalTime;

                $schedule->save();
            }

            if ($totalWorkTime < 20) {
                DB::rollBack();
                $response = [
                    'status' => self::STATUS_ERROR,
                    'message' => 'You must work at least 20 hours a week.',
                ];
            }

            DB::commit();
            $response = [
                'status' => self::STATUS_SUCCESS,
                'message' => 'Schedule has submitted successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => self::STATUS_ERROR,
                'message' => 'Invalid operation.',
            ];

            return $response;
        }

        return $response;
    }
}
