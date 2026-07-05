<?php

namespace App\Http\Controllers\V2;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Attendances\ReportService;

class ReportController extends Controller
{
    private ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $request->validate([
            'start' => 'nullable|date_format:Y-m-d',
            'end'   => 'nullable|date_format:Y-m-d',
        ]);

        return Inertia::render('attendances/Report', [
            'weekly'    => $this->service->getWeeklyReport(),
            'monthly'   => $this->service->getMonthlyReport(),
            'ranged'    => $this->service->getRangedReport($request->input('start'), $request->input('end')),
        ]);
    }
}
