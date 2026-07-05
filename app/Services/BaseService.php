<?php

namespace App\Services;

use App\Models\User;
use App\Enums\DayEnum;
use Illuminate\Support\Carbon;

class BaseService
{
    protected int $hourInSeconds = 3600;

    /**
     * @return User|null
     */
    public function getAuthUser(): User|null
    {
        return auth()->user();
    }

    /**
     * @param ?string $start
     * @param ?string $end
     * @return array
     */
    public function calculateWorkTime(?string $start, ?string $end): array
    {
        if ($start == null || $end == null) {
            return [
                'start'     => null,
                'end'       => null,
                'totalTime' => 0
            ];
        }

        $today = today()->toDateString();

        $start = Carbon::parse("$today $start");
        $end = Carbon::parse("$today $end");

        $breakStart = Carbon::parse("$today 12:00:00");
        $breakEnd = Carbon::parse("$today 13:00:00");

        $totalTime = $start->diffInSeconds($end);
        if ($start->isBefore($breakEnd) && $end->isAfter($breakStart)) {
            $overtimeStart = $start->isBefore($breakStart) ? $breakStart : $start;
            $overtimeEnd = $end->isAfter($breakEnd) ? $breakEnd : $end;
            $overtime = $overtimeStart->diffInSeconds($overtimeEnd);
            $totalTime -= $overtime;
        }

        return compact('start', 'end', 'totalTime');
    }

    /**
     * @param int range(1, 7) $dayOfWeekIso
     * @return DayEnum
     */
    public function convertDayIso(int $dayOfWeekIso)
    {
        return DayEnum::fromIso($dayOfWeekIso);
    }
}
