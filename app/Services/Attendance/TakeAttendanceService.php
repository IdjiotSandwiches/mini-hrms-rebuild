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
        return Attendance::where('user_id', $this->getUser()->user_id)
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
        if (!$attendance->exists()) return false;
        else {
            if (!$attendance->first()->check_out) return true;
            return false;
        }
    }

    public function checkInValidation()
    {
        try {
            DB::beginTransaction();

            $currentTime = $this->convertTime(Carbon::now());

            if (!$this->isWork($currentTime)) {
                DB::rollBack();
                return back()->with([
                    'status' => 'error',
                    'message' => 'You do not have work schedule today.'
                ]);
            }

            $isLate = $this->isLate($currentTime);

            $attendance = new Attendance();
            $attendance->user_id = $this->getUser()->user_id;
            $attendance->check_in = $this->convertTime(Carbon::now());
            $attendance->date = $this->convertTime(Carbon::now())
                ->toDateString();
            $attendance->late = $isLate;
            $attendance->save();

            DB::commit();
            return back()->with([
                'status' => 'success',
                'message' => 'Checked In.',
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

            $checkInTime = $this->getTodayAttendance()
                ->first()
                ->check_in;

            $currentTime = $this->convertTime(Carbon::now());
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
                    'date' => $currentTime->toDateString(),
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

