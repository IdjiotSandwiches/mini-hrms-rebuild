<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:mark-as-absence')]
#[Description('Update user attendances absence status.')]
class MarkAsAbsence extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            return DB::transaction(function () {
                $rows = Attendance::whereNull('absence')
                    ->whereNull('check_out')
                    ->whereDate('check_in', now())
                    ->update(['absence' => true]);

                $this->info("Successfully mark {$rows} status.");
            });
        } catch (\Exception $e) {
            $this->error('Error: '.$e->getMessage());
        }
    }
}
