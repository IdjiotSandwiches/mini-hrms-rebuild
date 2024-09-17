<?php

namespace App\Services\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Services\BaseService;
use Illuminate\Support\Collection;
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

        $attendance = $this->weeklyMapping($attendances);
        $late = $this->dataGrouping($attendances, self::LATE_COLUMN);
        $early = $this->dataGrouping($attendances, self::EARLY_COLUMN);
        $absence = $this->dataGrouping($attendances, self::ABSENCE_COLUMN);

        return (object) compact('attendance', 'late', 'early', 'absence');
    }

    public function getMostOnTimeAndAbsence()
    {
        $attendances = Attendance::all()->groupBy('user_id')
            ->map(function ($attendance) {
                $onTime = $attendance->filter(function ($item) {
                    return $item->late === 0 && $item->early === 0;
                })->count();

                $absence = $attendance->filter(function ($item) {
                    return $item->absence === 1;
                })->count();

                return (object) [
                    'onTime' => $onTime,
                    'absence' => $absence,
                ];
            });

        $onTimeUser = $this->getUserInfo($attendances, 'onTime');
        $absenceUser = $this->getUserInfo($attendances, 'absence');

        return (object) compact('onTimeUser', 'absenceUser');
    }

    public function getUserInfo(Collection $attendance, string $key)
    {
        if ($attendance->isEmpty()) return null;

        $sorted = $attendance->sortByDesc($key);

        $id = $sorted->keys()
            ->first();

        $count = $sorted->first()
            ->$key;

        $user = User::find($id);
        $userFullName = "$user->first_name $user->last_name";
        return (object) compact('userFullName', 'count');
    }

    public function count(object $attendances, string $columnName)
    {
        return $attendances
            ->where($columnName, true)
            ->count();
    }

    public function weeklyMapping($attendances)
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
        $attendances = $this->weeklyMapping($attendances);

        return $attendances;
    }
}
