<?php

namespace App\Services\Attendances;

use App\Models\Attendance;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ReportService extends BaseService
{
    private int $paginate = 10;

    /**
     * @param ?string $start
     * @param ?string $end
     * @return array
     */
    public function getRangedReport(?string $start, ?string $end): array
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end)->endOfDay();

        $attendances = Attendance::where('user_id', $this->getAuthUser()->id)
            ->whereBetween('check_in', [$start, $end]);

        $report = $this->getReport($attendances, 'ranged');
        return $report;
    }

    /**
     * @return array
     */
    public function getWeeklyReport(): array
    {
        $startOfWeek = now()->startOfWeek();
        $attendances = Attendance::where('user_id', $this->getAuthUser()->id)
            ->whereDate('check_in', '>=', $startOfWeek);

        $report = $this->getReport($attendances, 'weekly');
        return $report;
    }

    /**
     * @return array
     */
    public function getMonthlyReport(): array
    {
        $attendances = Attendance::where('user_id', $this->getAuthUser()->id)
            ->whereMonth('check_in', now()->month);

        $report = $this->getReport($attendances, 'monthly');
        return $report;
    }

    /**
     * @param Attendance|Builder $attendances
     * @param string $paginationName
     * @return array
     */
    private function getReport(Attendance|Builder $attendances, string $paginationName): array
    {
        $hours = floor($attendances->sum('duration') / $this->hourInSeconds);
        $attendances = $attendances
            ->orderByDesc('check_in')
            ->paginate($this->paginate, ['*'], $paginationName);

        return compact('attendances', 'hours');
    }
}
