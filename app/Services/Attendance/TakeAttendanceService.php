<?php

namespace App\Services\Attendance;

use Carbon\Carbon;
use Carbon\Factory;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class TakeAttendanceService extends BaseService
{
    private $currentTime;

    public function __construct()
    {
        $this->currentTime = $this->getCurrentTime();
    }

    public function getAttendance()
    {
        return Attendance::where('user_id', $this->getUser()->user_id)
            ->whereDate('date', $this->currentTime->toDateString());
    }

    public function isLate($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;
        $startTime = Carbon::createFromTimeString($schedule[$currentTimeDay]->start_time);

        if ($currentTime->gt($startTime)) return true;

        return false;
    }

    public function isEarly($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;
        $endTime = Carbon::createFromTimeString($schedule[$currentTimeDay]->end_time);

        if ($currentTime->lt($endTime)) return true;

        return false;
    }

    public function isWork($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;

        if ($schedule[$currentTimeDay]->work_time) return true;

        return false;
    }

    public function isCheckedIn()
    {
        $attendance = $this->getAttendance();
        if (!$attendance->exists()) return false;
        else {
            if (!$attendance->first()->check_out) return true;
            return false;
        }
    }

    public function isAbsence()
    {

    }

    public function checkInValidation()
    {
        try {
            DB::beginTransaction();

            $currentTime = $this->getCurrentTime();

            if (!$this->isWork($currentTime)) {
                DB::rollBack();
                return back()->with([
                    'status' => 'error',
                    'message' => 'You do not have work schedule today.'
                ]);
            }

            $isLate = $this->isLate($currentTime);

            Attendance::updateOrCreate(
                ['user_id' => $this->getUser()->user_id],
                [
                    'check_in' => $this->getCurrentTime(),
                    'date' => $this->getCurrentTime()
                        ->toDateString(),
                    'late' => $isLate,
                ]
            );

            DB::commit();
            return back()->with([
                'status' => 'success',
                'message' => 'Checked In',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }

    public function checkOutValidation()
    {
        try {
            DB::beginTransaction();

            $checkInTime = $this->getAttendance()
                ->first()
                ->check_in;

            $currentTime = $this->getCurrentTime();
            $diffTime = $currentTime->diffInMinutes($checkInTime);

            if ($diffTime < 60) {
                DB::rollBack();
                return back()->withErrors([
                    'attendanceError' => 'You need at least 1 hour to check out.'
                ]);
            }

            $isEarly = $this->isEarly($currentTime);

            Attendance::updateOrCreate(
                [
                    'user_id' => $this->getUser()
                        ->user_id,
                    'date' => $this->getCurrentTime()
                        ->toDateString(),
                ],
                [
                    'check_out' => $currentTime,
                    'early' => $isEarly,
                ]
            );

            DB::commit();
            return back()->with([
                'status' => 'success',
                'message' => 'Checked Out',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }
}

