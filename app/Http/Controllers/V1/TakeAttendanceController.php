<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Exceptions\TakeAttendanceException;
use App\Services\Attendances\TakeAttendanceService;

class TakeAttendanceController extends Controller
{
    private TakeAttendanceService $service;

    public function __construct(TakeAttendanceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('attendance.take-attendance.index', [
            'isCheckedIn' => $this->service->isCheckedIn()
        ]);
    }

    public function store()
    {
        try {
            $isCheckedOut = $this->service->attendance();
            $message = $isCheckedOut ? 'Checked Out.' : 'Checked In.';

            return back()->with([
                'status' => 'success',
                'message' => $message,
            ]);
        } catch (TakeAttendanceException $e) {
            return back()->with([
                'status' => 'warning',
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }
}
