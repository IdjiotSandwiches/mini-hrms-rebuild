<?php

namespace App\Services\Attendance;

use App\Interfaces\AttendanceInterface;
use App\Interfaces\PagiantionInterface;
use App\Interfaces\TimeFormatInterface;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Services\BaseService;

class ReportService extends BaseService implements
    AttendanceInterface,
    PagiantionInterface,
    TimeFormatInterface
{
    /**
     * @param Attendance|\Illuminate\Database\Eloquent\Builder
     * @param string
     * @return object
     */
    public function getReport($attendances, $paginationName)
    {
        $workHours = $this->calculateWorkHours($attendances);
        $attendances = $attendances->paginate(7, ['*'], $paginationName)
            ->through(function ($attendance) {
                return $this->reportConversion($attendance);
            });

        return (object) compact('attendances', 'workHours');
    }

    /**
     * @param string
     * @param string
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|void
     */
    public function getRangeReport($startTime, $endTime)
    {
        if (!$startTime || !$endTime) return;

        $startTime = $this->convertTime($startTime)
            ->toDateString();
        $endTime = $this->convertTime($endTime)
            ->toDateString();

        $attendance = $this->getAttendance();
        $rangeReport = $attendance->whereBetween(self::DATE_COLUMN, [$startTime, $endTime])
            ->get()
            ->map(function($report) {
                return $this->reportConversion($report);
            });

        return $rangeReport;
    }

    /**
     * @return object
     */
    public function getWeeklyReport()
    {
        $currentTime = $this->convertTime(Carbon::now());
        $startOfWeek = $currentTime->copy()
            ->startOfWeek()
            ->toDateString();

        $attendances = $this->getAttendance()
            ->whereBetween(self::DATE_COLUMN, [$startOfWeek, $currentTime->toDateString()]);

        return $this->getReport($attendances, self::WEEKLY_PAGINATION);
    }

    /**
     * @return object
     */
    public function getMonthlyReport()
    {
        $currentTime = $this->convertTime(Carbon::now());

        $attendances = $this->getAttendance()
            ->whereMonth(self::DATE_COLUMN, $currentTime->month);

        return $this->getReport($attendances, self::MONTHLY_PAGINATION);
    }

    /**
     * @param Attendance|\Illuminate\Database\Eloquent\Builder
     * @return float
     */
    public function calculateWorkHours($attendances)
    {
        $workHours = $attendances->get()
            ->map(function ($workTime) {
                return $this->convertWorkTime($workTime);
            })
            ->sum(function ($workTime) {
                return $workTime == '-' ? 0 : $this->convertTime($workTime)->diffInSeconds('00:00:00');
            });

        $workHours = floor($workHours / 3600);

        return $workHours;
    }

    /**
     * @param Attendance|\Illuminate\Database\Eloquent\Builder
     * @return object
     */
    public function reportConversion($attendance)
    {
        $date = $this->convertTime($attendance->date)
            ->format(self::DATE_FORMAT);
        $checkIn = $this->convertTime($attendance->check_in)
            ->format(self::TIME_FORMAT);
        $checkOut = $attendance->check_out ?
            $this->convertTime($attendance->check_out)
                ->format(self::TIME_FORMAT) : '-';
        $early = $this->convertBoolean($attendance->early);
        $late = $this->convertBoolean($attendance->late);
        $absence = $this->convertBoolean($attendance->absence);
        $workTime = $this->convertWorkTime($attendance);

        return (object) compact(
            'date',
            'checkIn',
            'checkOut',
            'early',
            'late',
            'absence',
            'workTime'
        );
    }

    /**
     * @param bool
     * @return string
     */
    public function convertBoolean($condition)
    {
        if (is_null($condition)) return '-';
        else {
            if ($condition) return 'Yes';
            return 'No';
        }
    }

    /**
     * @param Attendance|\Illuminate\Database\Eloquent\Builder
     * @return string
     */
    public function convertWorkTime($attendance)
    {
        return $attendance->check_out ? gmdate(self::TIME_FORMAT,
            $this->calculateWorkTime($this->convertTime($attendance->check_in),
            $this->convertTime($attendance->check_out))->totalTime) : '-';
    }
}
