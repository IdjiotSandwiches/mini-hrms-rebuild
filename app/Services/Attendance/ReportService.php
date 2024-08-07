<?php

namespace App\Services\Attendance;

use Carbon\Carbon;
use App\Services\BaseService;

class ReportService extends BaseService
{
    public function getReport($attendances, $paginationName)
    {
        $workHours = $this->calculateWorkHours($attendances);
        $attendances = $attendances->paginate(7, ['*'], $paginationName)
            ->through(function ($attendance) {
                return $this->reportConversion($attendance);
            });

        return (object) compact('attendances', 'workHours');
    }

    public function getRangeReport($startTime, $endTime)
    {
        if (!$startTime || !$endTime) return;

        $startTime = $this->convertTime($startTime)
            ->toDateString();
        $endTime = $this->convertTime($endTime)
            ->toDateString();

        $attendance = $this->getAttendance();
        $rangeReport = $attendance->whereBetween('date', [$startTime, $endTime])
            ->get()
            ->map(function($report) {
                return $this->reportConversion($report);
            });

        return $rangeReport;
    }

    public function getWeeklyReport()
    {
        $currentTime = $this->convertTime(Carbon::now());
        $startOfWeek = $currentTime->copy()
            ->startOfWeek()
            ->toDateString();

        $attendances = $this->getAttendance()
            ->whereBetween('date', [$startOfWeek, $currentTime->toDateString()]);

        return $this->getReport($attendances, 'weekly');
    }

    public function getMonthlyReport()
    {
        $currentTime = $this->convertTime(Carbon::now());

        $attendances = $this->getAttendance()
            ->whereMonth('date', $currentTime->month);

        return $this->getReport($attendances, 'monthly');
    }

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

    public function reportConversion($attendance)
    {
        $date = $this->convertTime($attendance->date)
            ->format('l, d F Y');
        $checkIn = $this->convertTime($attendance->check_in)
            ->format('H:i:s');
        $checkOut = $attendance->check_out ?
            $this->convertTime($attendance->check_out)
                ->format('H:i:s') : '-';
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

    public function convertBoolean($condition)
    {
        if (is_null($condition)) return '-';
        else {
            if ($condition) return 'Yes';
            return 'No';
        }
    }

    public function convertWorkTime($attendance)
    {
        return $attendance->check_out ? gmdate('H:i:s',
            $this->calculateWorkTime($this->convertTime($attendance->check_in),
            $this->convertTime($attendance->check_out))->totalTime) : '-';
    }
}
