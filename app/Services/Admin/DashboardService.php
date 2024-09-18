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
     * Method to get daily check in & out, attendances.
     *
     * @return object
     */
    public function getDailyAttendance(): object
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
     * Method to get weekly late, early, absence, and attendance.
     *
     * @return object
     */
    public function getWeeklyAttendance(): object
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
     * Method to map most on time & most absence user.
     *
     * @return object
     */
    public function getMostOnTimeAndAbsence(): object
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
     * Method to map most on time & most absence user.
     *
     * @param Collection|string
     * @return object|null
     */
    public function getUserInfo(Collection $attendance, string $key): object|null
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
     * Method to count based on column.
     *
     * @param object|string
     * @return int
     */
    public function count(object $attendances, string $columnName): int
    {
        return $attendances
            ->where($columnName, true)
            ->count();
    }

    /**
     * Method to map weekly attendance data.
     *
     * @param Collection
     * @return object
     */
    public function weeklyMapping(Collection $attendances): object
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
     * Method to group data based on column name.
     *
     * @param Collection|string
     * @return object
     */
    public function dataGrouping(Collection $attendances, string $columnName): object
    {
        $attendances = $attendances->where($columnName, true);
        $attendances = $this->weeklyMapping($attendances);

        return $attendances;
    }
}
