<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::create(2024, 6, 20); // Starting date
        $endDate = Carbon::create(2024, 7, 21);  // Ending date
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            DB::table('attendances')->insert([
                'user_id' => 1,
                'check_in' => $currentDate->setTime(8, 0, 0),
                'check_out' => $currentDate->setTime(18, 0, 0),
                'early' => false,
                'late' => false,
                'absence' => false,
                'date' => $currentDate->toDateString()
            ]);
            $currentDate->addDay();
        }
    }
}
