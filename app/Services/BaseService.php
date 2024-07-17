<?php

namespace App\Services;

class BaseService
{
    public function getUser()
    {
        return auth()->user();
    }
}

