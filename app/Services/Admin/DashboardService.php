<?php

namespace App\Services\Admin;

use App\Services\BaseService;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Interfaces\AttendanceInterface;

class DashboardService extends BaseService implements AttendanceInterface
{
    private $currentTime;

    public function __construct()
    {
        $this->currentTime = $this->convertTime(Carbon::now());
    }

    public function getDailyAttendance()
    {
        $attendances = Attendance::whereDate('date', $this->currentTime)
            ->get();

        $checkInOut = (object) [
            'checkedIn' => $attendances
                ->whereNotNull(self::CHECK_IN_COLUMN)
                ->count(),
            'checkedOut' => $attendances
                ->whereNotNull(self::CHECK_OUT_COLUMN)
                ->count(),
        ];

        $attendances = $this->dataGrouping($attendances)
            ->first();

        return (object) compact('checkInOut', 'attendances');
    }

    public function getWeeklyAttendance()
    {
        $startOfWeek = $this->currentTime
            ->startOfWeek()
            ->toDateString();
        $endOfWeek = $this->currentTime
            ->endOfWeek()
            ->toDateString();
        $attendances = Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();
        $attendances = $this->dataGrouping($attendances);

        return (object) $attendances;
    }

    public function dataGrouping($attendances)
    {
        $attendances = $attendances->groupBy(function ($attendance) {
            $key = $this->convertTime($attendance->date)
                ->shortEnglishDayOfWeek;
            return $key;
        })->map(function ($attendance) {
            return (object) [
                'attendance' => $attendance->count(),
                'late' => $this->filterCountItem($attendance, self::LATE_COLUMN),
                'early' => $this->filterCountItem($attendance, self::EARLY_COLUMN),
                'absence' => $this->filterCountItem($attendance, self::ABSENCE_COLUMN),
            ];
        });

        return (object) $attendances;
    }

    public function filterCountItem($attendance, $columnName)
    {
        return $attendance->filter(function ($day) use ($columnName) {
            return $day->whereNotNull($columnName)
                ->where($columnName, 1);;
        })->count();
    }
}
