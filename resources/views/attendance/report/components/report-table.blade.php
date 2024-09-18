@props(['attendances'])
<div class="relative overflow-x-auto sm:rounded-lg">
    <table class="w-full text-sm text-center rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">No.</th>
                <th scope="col" class="px-6 py-3">Date</th>
                <th scope="col" class="px-6 py-3">Check In Time</th>
                <th scope="col" class="px-6 py-3">Check Out Time</th>
                <th scope="col" class="px-6 py-3">Early</th>
                <th scope="col" class="px-6 py-3">Late</th>
                <th scope="col" class="px-6 py-3">Absence</th>
                <th scope="col" class="px-6 py-3">Work Duration</th>
            </tr>
        </thead>
        <tbody>
            @if ($attendances->isEmpty())
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td colspan="8" class="px-6 py-3 text-center">You do not have work attendance</td>
                </tr>
            @endif
            @foreach ($attendances as $key => $value)
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $key + $attendances->firstItem() }}</th>
                    <td class="px-6 py-4">{{ $value->date }}</td>
                    <td class="px-6 py-4">{{ $value->checkIn }}</td>
                    <td class="px-6 py-4">{{ $value->checkOut }}</td>
                    <td class="px-6 py-4">{{ $value->early }}</td>
                    <td class="px-6 py-4">{{ $value->late }}</td>
                    <td class="px-6 py-4">{{ $value->absence }}</td>
                    <td class="px-6 py-4">{{ $value->workTime }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
