<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\Factory;
use App\Models\Schedule;

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
        return Schedule::where('user_id', $this->getUser()->user_id);
    }
}

