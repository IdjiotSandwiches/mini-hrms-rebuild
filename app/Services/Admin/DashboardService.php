<?php

namespace App\Services\Admin;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Interfaces\AttendanceInterface;

class DashboardService implements AttendanceInterface
{
    public function getAttendanceCount()
    {
        $attendances = Attendance::whereDate('date', Carbon::now())->get();
        $attendances = [
            'checkIn' => $attendances->whereNotNull(self::CHECK_IN_COLUMN)->count(),
            'checkOut' => $attendances->whereNotNull(self::CHECK_OUT_COLUMN)->count(),
            'late' => $this->countItem($attendances, self::LATE_COLUMN),
            'early' => $this->countItem($attendances, self::EARLY_COLUMN),
            'absence' => $this->countItem($attendances, self::ABSENCE_COLUMN),
        ];

        return (object) $attendances;
    }

    public function countItem($attendances, $columnName)
    {
        return $attendances->whereNotNull($columnName)
            ->where($columnName, 1)
            ->count();
    }
}
