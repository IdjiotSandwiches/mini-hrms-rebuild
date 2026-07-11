<?php

namespace App\Services\Attendances;

use App\Exceptions\TakeAttendanceException;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Services\BaseService;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TakeAttendanceService extends BaseService
{
    private int $minimumWorkSeconds = 60;

    public function isCheckedIn(): bool
    {
        $userId = $this->getAuthUser()->id;
        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('check_in', now());

        if (! $attendance->exists()) {
            return false;
        } else {
            $attendance = $attendance->first();

            $day = $this->convertDayIso($attendance->check_in->dayOfWeekIso);
            $scheduleExists = Schedule::where('user_id', $userId)
                ->where('day', $day)
                ->exists();

            if (! $attendance->check_out && $scheduleExists) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function attendance(): bool
    {
        $userId = $this->getAuthUser()->id;
        $schedules = Schedule::where('user_id', $userId)
            ->get();

        if ($schedules->count() == 0) {
            throw TakeAttendanceException::noSchedules();
        }

        $current = now();
        $day = $this->convertDayIso($current->dayOfWeekIso);
        $hasWorkSchedule = $schedules->where('day', $day)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->first();

        if (! $hasWorkSchedule) {
            throw TakeAttendanceException::noWorkSchedule();
        }

        return DB::transaction(function () use ($userId, $schedules, $current) {
            $attendance = Attendance::where('user_id', $userId)
                ->whereDate('check_in', now());

            if (! $attendance->exists()) {
                $isLate = $this->isLate($schedules, $current);
                Attendance::create([
                    'user_id' => $userId,
                    'check_in' => $current,
                    'late' => $isLate,
                ]);

                return false;
            } else {
                $attendance = $attendance->first();
                if ($attendance->check_out) {
                    throw TakeAttendanceException::alreadyTakeAttendance();
                }

                $checkInTime = $attendance->check_in;
                $diffTime = $checkInTime->diffInSeconds($current);
                if ($diffTime < $this->minimumWorkSeconds) {
                    throw TakeAttendanceException::minimumWork();
                }

                $work = $this->calculateWorkTime(
                    $attendance->check_in->toTimeString(),
                    $current->toTimeString()
                );

                $isEarly = $this->isEarly($schedules, $current);
                $attendance->update([
                    'check_out' => $current,
                    'early' => $isEarly,
                    'duration' => $work['totalTime'],
                ]);

                return true;
            }
        });
    }

    private function isLate(Collection $schedules, Carbon|CarbonInterface $now): bool
    {
        $day = $this->convertDayIso($now->dayOfWeekIso);
        $schedule = $schedules->where('day', $day)
            ->first();
        $startTime = Carbon::parse($schedule->start_time);

        return $now->gt($startTime);
    }

    private function isEarly(Collection $schedules, Carbon|CarbonInterface $now): bool
    {
        $day = $this->convertDayIso($now->dayOfWeekIso);
        $schedule = $schedules->where('day', $day)
            ->first();
        $endTime = Carbon::parse($schedule->end_time);

        return $now->lt($endTime);
    }
}
