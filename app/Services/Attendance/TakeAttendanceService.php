<?php

namespace App\Services\Attendance;

use Carbon\Carbon;
use App\Models\Attendance;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class TakeAttendanceService extends BaseService
{
    public function getTodayAttendance()
    {
        return $this->getAttendance()
            ->whereDate('date', $this->convertTime(Carbon::now())
                ->toDateString());
    }

    public function isLate($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;
        $startTime = $this->convertTime($schedule[$currentTimeDay]->start_time);

        return $currentTime->gt($startTime);
    }

    public function isEarly($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;
        $endTime = $this->convertTime($schedule[$currentTimeDay]->end_time);

        return $currentTime->lt($endTime);
    }

    public function isWork($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;

        return $schedule[$currentTimeDay]->work_time;
    }

    public function isCheckedIn()
    {
        $attendance = $this->getTodayAttendance();
        $schedule = $this->getSchedule();

        if (!$attendance->exists()) return false;
        else {
            if (!$attendance->first()->check_out && $schedule->exists()) return true;
            return false;
        }
    }

    public function attendanceValidation()
    {
        try {
            DB::beginTransaction();

            if (!$this->getSchedule()
                ->exists()) {
                    DB::rollBack();
                    $response = [
                        'status' => 'warning',
                        'message' => 'Input schedule first',
                    ];

                    return back()->with($response);
            }

            $currentTime = $this->convertTime(Carbon::now());
            if (!$this->isWork($currentTime)) {
                DB::rollBack();
                $response = [
                    'status' => 'error',
                    'message' => 'You do not have work schedule today.'
                ];

                return back()->with($response);
            }

            $todayAttendance = $this->getTodayAttendance();
            if (!$todayAttendance->exists()) {
                $isLate = $this->isLate($currentTime);

                $attendance = new Attendance();
                $attendance->user_id = $this->getUser()->id;
                $attendance->check_in = $this->convertTime(Carbon::now());
                $attendance->date = $this->convertTime(Carbon::now())
                    ->toDateString();
                $attendance->late = $isLate;
                $attendance->save();

                $response = [
                    'status' => 'success',
                    'message' => 'Checked In.',
                ];
            }
            else {
                $todayAttendance = $todayAttendance->first();

                if ($todayAttendance->check_out) {
                    DB::rollBack();
                    $response = [
                        'status' => 'warning',
                        'message' => 'You have already checked in today.',
                    ];

                    return back()->with($response);
                }

                $checkInTime = $todayAttendance->check_in;
                $diffTime = $currentTime->diffInMinutes($checkInTime);

                if ($diffTime < 60) {
                    DB::rollBack();
                    $response = [
                        'status' => 'warning',
                        'message' => 'You need at least 1 hour to check out.',
                    ];

                    return back()->with($response);
                }

                $isEarly = $this->isEarly($currentTime);

                $todayAttendance->check_out = $currentTime;
                $todayAttendance->early = $isEarly;
                $todayAttendance->save();

                $response = [
                    'status' => 'success',
                    'message' => 'Checked Out',
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => 'error',
                'message' => 'Invalid operation.',
            ];

            return back()->with($response);
        }

        return back()->with($response);
    }
}

