<?php

namespace App\Services\Attendances;

use App\Models\Schedule;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InputScheduleException;

class InputScheduleService extends BaseService
{
    private int $requiredTime = 20;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Schedule>|\Illuminate\Support\Collection<int, \stdClass>
     */
    public function getSchedules(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    {
        $schedules = Schedule::where('user_id', $this->getAuthUser()->id)
            ->get();

        return $schedules;
    }

    /**
     * @return float
     */
    public function calculateTotalWorkHour(): float
    {
        $totalWorkHour = Schedule::where('user_id', $this->getAuthUser()->id)
            ->sum('work_time');

        $totalWorkHour = floor($totalWorkHour / $this->hourInSeconds);
        return $totalWorkHour;
    }

    /**
     * @return bool
     */
    public function canUpdateSchedule(): bool
    {
        $schedules = Schedule::where('user_id', $this->getAuthUser()->id)
            ->get();
        if ($schedules->count() == 0) return true;

        $schedule = $schedules->first();
        if (now()->diffInMonths($schedule->updated_at) >= 3) return true;

        return false;
    }

    /**
     * @param array $validated
     * @throws \Exception
     * @return void
     */
    public function processSchedule(array $validated): void
    {
        DB::transaction(function () use ($validated) {
            $userId = $this->getAuthUser()->id;
            $totalWorkTime = 0;
            $schedules = collect();

            $data = collect($validated['schedules'])->keyBy('day');
            foreach ($data as $day => $value) {
                $val = (array) $value;
                $time = $this->calculateWorkTime($val['start'], $val['end']);
                $totalWorkTime += $time['totalTime'];

                $schedule = new Schedule();
                $schedule->user_id = $userId;
                $schedule->day = $day;
                $schedule->start_time = $time['start'];
                $schedule->end_time = $time['end'];
                $schedule->work_time = $time['totalTime'];

                $schedules->add($schedule);
            }

            if (($totalWorkTime / $this->hourInSeconds) < $this->requiredTime) {
                throw InputScheduleException::doesNotMeetHoursRequirement();
            }

            Schedule::upsert(
                $schedules->toArray(),
                ['user_id', 'day'],
                ['start_time', 'end_time', 'work_time']
            );
        });
    }
}
