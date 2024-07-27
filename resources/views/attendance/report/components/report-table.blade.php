@props(['attendances'])
<div class="w-full relative overflow-x-auto rounded-md">
    <table class="w-full table-auto text-center text-gray-500 dark:text-white">
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
            @if ($attendances->isEmpty())
                <tr class="border-b-2 border-gray-200">
                    <td colspan="8" class="px-4 py-3">
                        You do not have work attendance
                    </td>
                </tr>
            @endif
            @foreach ($attendances as $key => $value)
                <tr class="border-b-2 border-gray-200">
                    <td class="px-4 py-3 dark:bg-gray-700">{{ $key + $attendances->firstItem() }}</td>
                    <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">{{ $value->date }}</td>
                    <td class="px-4 py-3 dark:bg-gray-700">{{ $value->checkIn }}</td>
                    <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">{{ $value->checkOut }}</td>
                    <td class="px-4 py-3 dark:bg-gray-700">{{ $value->early }}</td>
                    <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">{{ $value->late }}</td>
                    <td class="px-4 py-3 dark:bg-gray-700">{{ $value->absence }}</td>
                    <td class="px-4 py-3 bg-gray-100 dark:bg-gray-800">{{ $value->workTime }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
