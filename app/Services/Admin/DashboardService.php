<?php

namespace App\Services\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Services\BaseService;
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
            'checkedIn' => $this->count($attendances, self::CHECK_IN_COLUMN),
            'checkedOut' => $this->count($attendances, self::CHECK_OUT_COLUMN),
        ];

        $attendances = (object) [
            'late' => $this->count($attendances, self::LATE_COLUMN),
            'early' => $this->count($attendances, self::EARLY_COLUMN),
            'absence' => $this->count($attendances, self::ABSENCE_COLUMN),
        ];

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

        $attendance = $this->groupMapping($attendances);
        $late = $this->dataGrouping($attendances, self::LATE_COLUMN);
        $early = $this->dataGrouping($attendances, self::EARLY_COLUMN);
        $absence = $this->dataGrouping($attendances, self::ABSENCE_COLUMN);

        return (object) compact('attendance', 'late', 'early', 'absence');
    }

    public function getMostOnTimeAndLate()
    {
        $attendances = User::with('attendance')->get();
        dd($attendances->first()->attendance);
    }

    public function count($attendances, $columnName)
    {
        return $attendances
            ->where($columnName, true)
            ->count();
    }

    public function groupMapping($attendances)
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $placeholder = [];
        foreach ($days as $day) {
            $item = [
                'x' => $day,
                'y' => 0,
            ];
            array_push($placeholder, $item);
        }

        $attendances = $attendances->groupBy(function ($attendance) {
            return $this->convertTime($attendance->date)
                ->shortEnglishDayOfWeek;
        })->map(function ($attendance, $key) {
            return (object) [
                'x' => $key,
                'y' => $attendance->count(),
            ];
        })->values();

        $attendances = array_replace($placeholder, $attendances->toArray());

        return (object) [$attendances];
    }

    public function dataGrouping($attendances, $columnName)
    {
        $attendances = $attendances->where($columnName, true);
        $attendances = $this->groupMapping($attendances);

        return $attendances;
    }
}
