<?php

namespace App\Services\Attendance;

use Carbon\Carbon;
use Carbon\Factory;
use App\Models\Attendance;
use App\Services\BaseService;

class TakeAttendanceService extends BaseService
{
    private $user;

    public function __construct()
    {
        $this->user = $this->getUser();
    }

    public function getCurrentTime()
    {
        $factoryTime = new Factory([
            'timezone' => 'Asia/Jakarta'
        ]);

        return $factoryTime->make(Carbon::now());
    }

    public function getAttendance()
    {
        return Attendance::where('user_id', $this->getUser()->user_id)
        ->whereDate('date', $this->getCurrentTime()->toDateString());
    }

    public function isCheckedIn()
    {
        $attendance = $this->getAttendance();
        if (!$attendance->exists()) return false;
        else {
            if (!$attendance->first()->check_out) return true;
            else if ($attendance->first()->check_out) return false;
        }
    }

}

