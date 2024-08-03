@extends('attendance.layouts.attendance-layout', with(['title' => 'Report', 'desc' => 'This is where your weekly and monthly work report will be displayed.']))
@section('title', 'Attendance - Report')

@section('content')
    <report-section class="flex flex-col gap-8 py-10">
        <current-date>
            <h1 class="text-lg font-medium">Current Date</h1>
            <p class="
                text-gray-500
                dark:text-gray-300
            ">Today is <span id="current-day"></span>.
                <span id="current-hours"></span>:<span id="current-minutes"></span>:<span id="current-seconds"></span>
            </p>
        </current-date>
        <weekly-table class="gap-4 flex flex-col">
            <div>
                <h1 class="text-lg font-medium">Weekly Report</h1>
                <p class="
                    text-gray-500
                    dark:text-gray-300
                ">This is your work report for the last 7 days.</p>
            </div>
            @include('attendance.report.components.report-table', with(['attendances' => $weeklyAttendances]))
            <p class="font-medium">Total Weekly Work Hours:
                <span class="@if ($weeklyWorkHours < 20) text-red-500 @else text-blue-500 @endif">{{ $weeklyWorkHours }} Hours</span>
            </p>
        </weekly-table>
        <monthly-table class="gap-4 flex flex-col">
            <div>
                <h1 class="text-lg font-medium">Monthly Report</h1>
                <p class="
                    text-gray-500
                    dark:text-gray-300
                ">This is your work report for the last 30 days.</p>
            </div>
            @include('attendance.report.components.report-table', with(['attendances' => $monthlyAttendances]))
            <p class="font-medium">Total Monthly Work Hours:
                <span class="@if ($monthlyWorkHours < 80) text-red-500 @else text-blue-500 @endif">{{ $monthlyWorkHours }} Hours</span>
            </p>
            {{ $monthlyAttendances->links('pagination::tailwind') }}
        </monthly-table>
    </report-section>
@endsection
