<?php

namespace App\Http\Controllers;

class InputScheduleController extends Controller
{
    public function index()
    {
        return view('attendance.input-schedule.index');
    }
}
