<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InputScheduleController extends Controller
{
    public function index()
    {
        return view('attendance.input-schedule.index');
    }

    public function inputSchedule(Request $request)
    {
        if (!$request->ajax()) abort(404);

        $validated = $request->validate([
            'schedule' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            $totalWorkTime = 0;
            foreach($validated['schedule'] as $day => $value) {
                $start = Carbon::createFromTimeString($value['start']);
                $end = Carbon::createFromTimeString($value['end']);

                $breakStart = Carbon::createFromTimeString('12:00:00');
                $breakEnd = Carbon::createFromTimeString('13:00:00');
                $totalTime = $end->diffInSeconds($start);

                if ($start->isBefore($breakEnd) && $end->isAfter($breakStart)) {
                    $overtimeStart = $start->isBefore($breakStart) ? $breakStart : $start;
                    $overtimeEnd = $end->isAfter($breakEnd) ? $breakEnd : $end;
                    $overtime = $overtimeEnd->diffInSeconds($overtimeStart);
                    $totalTime -= $overtime;
                }

                $totalWorkTime += $totalTime;

                $schedule = new Schedule();
                $schedule->user_id = auth()->user()->user_id;
                $schedule->day = $day;
                $schedule->start_time = $start;
                $schedule->end_time = $end;
                $schedule->work_time = $value['start'] == '00:00:00' && $value['end'] == '00:00:00' ? 0 : $totalTime;
                $schedule->save();
            }

            if ($totalWorkTime < 20) {
                DB::rollBack();
                return back()->with([
                    'status' => 'error',
                    'message' => 'You must work at least 20 hours a week.',
                ]);
            }

            DB::commit();
            return back()->with([
                'status' => 'success',
                'message' => 'Schedule has submitted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.'
            ]);
        }
    }
}
