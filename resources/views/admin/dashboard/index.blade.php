@extends('admin.layout', with(['title' => 'Dashboard', 'desc' => 'Review of registered users.']))
@section('title', 'Admin - Dashboard')

@section('content')
    <dashboard-section class="flex flex-col gap-8 py-10">
        <most-rank class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-center">
            <!-- Current checked in & out -->
            <div class="block max-w-screen-sm h-52 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <p class="font-normal text-gray-700 dark:text-gray-400">Name</p>
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Most On Time</h5>
            </div>
            <!-- Late, early, absence -->
            <div class="block max-w-screen-sm h-52 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <p class="font-normal text-gray-700 dark:text-gray-400">Name</p>
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Most Late</h5>
            </div>
        </most-rank>
    </dashboard-section>


@endsection
