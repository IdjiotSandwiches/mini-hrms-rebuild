<?php

namespace App\Services;

use Carbon\Factory;
use App\Models\Schedule;
use App\Models\Attendance;

class BaseService
{
    public function getUser()
    {
        return auth()->user();
    }

    public function convertTime($time)
    {
        $factoryTime = new Factory([
            'timezone' => 'Asia/Jakarta'
        ]);

        return $factoryTime->make($time);
    }

    public function getSchedule()
    {
        return Schedule::with('user')
            ->where('id', $this->getUser()->id);
    }

    public function getAttendance()
    {
        return Attendance::with('user')
            ->where('id', $this->getUser()->id);
    }

    public function calculateWorkTime($start, $end)
    {
        $start = $this->convertTime($start);
        $end = $this->convertTime($end);

        $breakStart = $this->convertTime('12:00:00');
        $breakEnd = $this->convertTime('13:00:00');
        $totalTime = $end->diffInSeconds($start);

        if ($start->isBefore($breakEnd) && $end->isAfter($breakStart)) {
            $overtimeStart = $start->isBefore($breakStart) ? $breakStart : $start;
            $overtimeEnd = $end->isAfter($breakEnd) ? $breakEnd : $end;
            $overtime = $overtimeEnd->diffInSeconds($overtimeStart);
            $totalTime -= $overtime;
        }

        return (object) compact('start', 'end', 'totalTime');
    }
}

