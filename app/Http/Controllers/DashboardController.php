<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function getData()
    {
        $attendances = Attendance::whereDate('date', Carbon::now())->get();
        // $attendances = $attendances->map(function($attendance) {
        //     $items = [
        //         'check_in' => (bool) $attendance->check_in,
        //         'check_out' => (bool) $attendance->check_out,
        //         'late' => (bool) $attendance->late,
        //         'early' => (bool) $attendance->early,
        //         'absence' => (bool) $attendance->absence,
        //     ];

        //     return (object) $items;
        // });
        $attendances = [
            $checkIn = $attendances->whereNotNull('check_in')->count(),
            $checkOut = $attendances->whereNotNull('check_out')->count(),
            $late = $this->count($attendances, 'late'),
            $early = $this->count($attendances, 'early'),
        ];
    }

    public function count($attendances, $columnName)
    {
        return $attendances->whereNotNull($columnName)
            ->where($columnName, 1)
            ->count();
    }
}
