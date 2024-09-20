<?php

namespace App\Services\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use App\Interfaces\DashboardInterface;
use App\Interfaces\AttendanceInterface;

class DashboardService extends BaseService implements
AttendanceInterface, DashboardInterface
{
    private $currentTime;

    public function __construct()
    {
        $this->currentTime = $this->convertTime(Carbon::now());
    }

    /**
     * @return object
     */
    public function getDailyAttendance()
    {
        $attendances = Attendance::whereDate(self::DATE_COLUMN, $this->currentTime)
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

    /**
     * @return object
     */
    public function getWeeklyAttendance()
    {
        $startOfWeek = $this->currentTime
            ->startOfWeek()
            ->toDateString();
        $endOfWeek = $this->currentTime
            ->endOfWeek()
            ->toDateString();
        $attendances = Attendance::whereBetween(self::DATE_COLUMN, [$startOfWeek, $endOfWeek])
            ->get();

        $attendance = $this->weeklyMapping($attendances);
        $late = $this->dataGrouping($attendances, self::LATE_COLUMN);
        $early = $this->dataGrouping($attendances, self::EARLY_COLUMN);
        $absence = $this->dataGrouping($attendances, self::ABSENCE_COLUMN);

        return (object) compact('attendance', 'late', 'early', 'absence');
    }

    /**
     * @return object
     */
    public function getMostOnTimeAndAbsence()
    {
        $attendances = Attendance::all()->groupBy(self::USER_ID_COLUMN)
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

        $onTimeUser = $this->getUserInfo($attendances, self::ON_TIME_KEY);
        $absenceUser = $this->getUserInfo($attendances, self::ABSENCE_KEY);

        return (object) compact('onTimeUser', 'absenceUser');
    }

    /**
     * @param Collection|string
     * @return object|null
     */
    public function getUserInfo($attendance, $key)
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

    /**
     * @param object|string
     * @return int
     */
    public function count($attendances, $columnName)
    {
        return $attendances
            ->where($columnName, true)
            ->count();
    }

    /**
     * @param Collection
     * @return object
     */
    public function weeklyMapping($attendances)
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $placeholder = [];
        foreach ($days as $day) {
            // Fit into apex chart data series format
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
                // Fit into apex chart data series format
                'x' => $key,
                'y' => $attendance->count(),
            ];
        })->values();

        $attendances = array_replace($placeholder, $attendances->toArray());

        return (object) [$attendances];
    }

    /**
     * @param Collection|string
     * @return object
     */
    public function dataGrouping($attendances, $columnName)
    {
        $attendances = $attendances->where($columnName, true);
        $attendances = $this->weeklyMapping($attendances);

        return $attendances;
    }
}
