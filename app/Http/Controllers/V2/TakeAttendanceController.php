<?php

namespace App\Http\Controllers\V2;

use Inertia\Inertia;
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
        return Inertia::render('attendances/TakeAttendance', [
            'isCheckedIn' => $this->service->isCheckedIn()
        ]);
    }

    public function store()
    {
        try {
            $isCheckedOut = $this->service->attendance();
            $message = $isCheckedOut ? 'Checked Out.' : 'Checked In.';

            return Inertia::flash(
                'toast', ['type' => 'success', 'message' => __($message)]
            )->back();
        } catch (TakeAttendanceException $e) {
            return Inertia::flash(
                'toast', ['type' => 'warning', 'message' => __($e->getMessage())]
            )->back();
        } catch (\Exception $e) {
            return Inertia::flash(
                'toast', ['type' => 'error', 'message' => __('An error has occurred while saving the data. Please try again.')]
            )->back();
        }
    }
}
