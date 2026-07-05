<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Models\Attendance;
use App\Services\BaseService;

class DashboardService extends BaseService
{
    /**
     * @return array
     */
    public function getDailyAttendance(): array
    {
        $attendances = Attendance::whereDate('check_in', now())
            ->selectRaw("
                COUNT(check_in) as check_in_count,
                COUNT(check_out) as check_out_count,
                SUM(late) as late_count,
                SUM(early) as early_count,
                SUM(absence) as absence_count
            ")->first();

        return [
            'attendances' => [
                'check_in'  => (int) $attendances->check_in_count ?? 0,
                'check_out' => (int) $attendances->check_out_count ?? 0
            ],
            'exceptions' => [
                'late'      => (int) $attendances->late_count ?? 0,
                'early'     => (int) $attendances->early_count ?? 0,
                'absence'   => (int) $attendances->absence_count ?? 0
            ]
        ];
    }

    /**
     * @return array
     */
    public function getWeeklyAttendance(): array
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $attendances = Attendance::whereBetween('check_in', [$startOfWeek, $endOfWeek])
            ->selectRaw("
                DATE_FORMAT(check_in, '%W') as day,
                COUNT(id) as attendance_count,
                SUM(late) as late_count,
                SUM(early) as early_count,
                SUM(absence) as absence_count
            ")
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $weeklyData = [];
        foreach ($daysOfWeek as $day) {
            $weeklyData[$day] = [
                'attendance' => (int) ($attendances[$day]->attendance_count ?? 0),
                'late'       => (int) ($attendances[$day]->late_count ?? 0),
                'early'      => (int) ($attendances[$day]->early_count ?? 0),
                'absence'    => (int) ($attendances[$day]->absence_count ?? 0),
            ];
        }

        return $weeklyData;
    }

    /**
     * @return array
     */
    public function getMostOnTimeAndAbsence(): array
    {
        $onTime = User::withCount(['attendances as count' =>
            fn($q) => $q->where('late', 0)
                ->where('early', 0)
        ])
            ->having('count', '>', 0)
            ->orderByDesc('count')
            ->first();

        $absence = User::withCount(['attendances as count' =>
            fn($q) => $q->where('absence', 1)
        ])
            ->having('count', '>', 0)
            ->orderByDesc('count')
            ->first();

        return [
            'onTime'    => $onTime,
            'absence'   => $absence
        ];
    }
}
