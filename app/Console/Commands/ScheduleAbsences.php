<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Console\Command;

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

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $yesterdayDate = Carbon::yesterday()->toDateString();

        User::chunk(1000, function ($users) use ($yesterdayDate) {
            foreach ($users as $user) {
                $attendance = Attendance::where([
                    ['user_id', $user->user_id],
                    ['date', $yesterdayDate]
                ]);

                $absence = (!$attendance->check_in || !$attendance->check_out) ? true : false;

                $attendance->update(['absence' => $absence]);
                $attendance->save();
            }
        });
    }
}
