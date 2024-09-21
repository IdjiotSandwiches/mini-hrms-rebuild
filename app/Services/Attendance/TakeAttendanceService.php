<?php

namespace App\Services\Attendance;

use App\Interfaces\AttendanceInterface;
use App\Interfaces\StatusInterface;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class TakeAttendanceService extends BaseService implements
    AttendanceInterface,
    StatusInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getTodayAttendance()
    {
        return $this->getAttendance()
            ->whereDate(self::DATE_COLUMN, $this->convertTime(Carbon::now())
                ->toDateString());
    }

    /**
     * @param \Carbon\Carbon
     * @return bool
     */
    public function isLate($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;
        $startTime = $this->convertTime($schedule[$currentTimeDay]->start_time);

        return $currentTime->gt($startTime);
    }

    /**
     * @param \Carbon\Carbon
     * @return bool
     */
    public function isEarly($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;
        $endTime = $this->convertTime($schedule[$currentTimeDay]->end_time);

        return $currentTime->lt($endTime);
    }

    /**
     * @param \Carbon\Carbon
     * @return bool
     */
    public function isWork($currentTime)
    {
        $schedule = $this->getSchedule()->get();
        $currentTimeDay = $currentTime->dayOfWeekIso - 1;

        return $schedule[$currentTimeDay]->work_time;
    }

    /**
     * @return bool
     */
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

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attendanceValidation()
    {
        try {
            DB::beginTransaction();

            if (!$this->getSchedule()
                ->exists()) {
                    DB::rollBack();
                    $response = [
                        'status' => self::STATUS_WARNING,
                        'message' => 'Input schedule first',
                    ];

                    return back()->with($response);
            }

            $currentTime = $this->convertTime(Carbon::now());
            if (!$this->isWork($currentTime)) {
                DB::rollBack();
                $response = [
                    'status' => self::STATUS_ERROR,
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
                    'status' => self::STATUS_SUCCESS,
                    'message' => 'Checked In.',
                ];
            }
            else {
                $todayAttendance = $todayAttendance->first();

                if ($todayAttendance->check_out) {
                    DB::rollBack();
                    $response = [
                        'status' => self::STATUS_WARNING,
                        'message' => 'You have already checked in today.',
                    ];

                    return back()->with($response);
                }

                $checkInTime = $todayAttendance->check_in;
                $diffTime = $currentTime->diffInMinutes($checkInTime);

                if ($diffTime < 60) {
                    DB::rollBack();
                    $response = [
                        'status' => self::STATUS_WARNING,
                        'message' => 'You need at least 1 hour to check out.',
                    ];

                    return back()->with($response);
                }

                $isEarly = $this->isEarly($currentTime);

                $todayAttendance->check_out = $currentTime;
                $todayAttendance->early = $isEarly;
                $todayAttendance->save();

                $response = [
                    'status' => self::STATUS_SUCCESS,
                    'message' => 'Checked Out',
                ];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => self::STATUS_ERROR,
                'message' => 'Invalid operation.',
            ];

            return back()->with($response);
        }

        return back()->with($response);
    }
}

