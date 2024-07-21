@extends('attendance.layouts.attendance-layout', with(['title' => 'Report', 'desc' => 'This is where your weekly and monthly work report will be displayed.']))
@section('title', 'Attendance - Report')

@section('content')
    <weekly-table class="py-10 gap-4 flex flex-col">
        <div class="w-full relative overflow-x-auto rounded-md">
            <table class="w-full table text-center text-gray-500">
                <thead class="bg-blue-500 text-white">
                    <tr class="font-semibold">
                        <td class="px-4 py-3">No.</td>
                        <td class="px-4 py-3 bg-blue-600">Date</td>
                        <td class="px-4 py-3">Check In Time</td>
                        <td class="px-4 py-3 bg-blue-600">Check Out Time</td>
                        <td class="px-4 py-3">Early</td>
                        <td class="px-4 py-3 bg-blue-600">Late</td>
                        <td class="px-4 py-3">Absence</td>
                        <td class="px-4 py-3 bg-blue-600">Work Duration</td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b-2 border-gray-200">
                        <td class="px-4 py-3">1</td>
                        <td class="px-4 py-3 bg-gray-100">Wednesday, 31 September 2020</td>
                        <td class="px-4 py-3">00:00:00</td>
                        <td class="px-4 py-3 bg-gray-100">00:00:00</td>
                        <td class="px-4 py-3">Yes</td>
                        <td class="px-4 py-3 bg-gray-100">Yes</td>
                        <td class="px-4 py-3">Yes</td>
                        <td class="px-4 py-3 bg-gray-100">00:00:00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </weekly-table>

@endsection
