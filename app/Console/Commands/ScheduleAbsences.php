<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use App\Services\BaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleAbsences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:absences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update absence status of each user';

    /**
     * Execute the console command.
     */

    private $baseService;

    public function __construct()
    {
        parent::__construct();
        $this->baseService = new BaseService();
    }

    public function handle()
    {
        try {
            $yesterdayDate = $this->baseService
                ->convertTime(Carbon::yesterday())
                ->toDateString();

            User::chunk(1000, function ($users) use ($yesterdayDate) {
                foreach ($users as $user) {
                    $attendance = Attendance::where([
                        ['user_id', $user->user_id],
                        ['date', $yesterdayDate]
                    ])->first();

                    if (!$attendance) continue;

                    $absence = !$attendance->check_in || !$attendance->check_out;

                    $attendance->absence = $absence;
                    $attendance->save();
                }
            });

            $this->info('Scheduler run successfully');
        } catch (\Exception $e) {
            Log::error('Absence scheduler error: ' . $e->getMessage());
            $this->error('Absence scheduler error: ' . $e->getMessage());
        }
    }
}
