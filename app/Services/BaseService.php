<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\Factory;

class BaseService
{
    public function getUser()
    {
        return auth()->user();
    }

    public function getCurrentTime()
    {
        $factoryTime = new Factory([
            'timezone' => 'Asia/Jakarta'
        ]);

        return $factoryTime->make(Carbon::now());
    }
}

