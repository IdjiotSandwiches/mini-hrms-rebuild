@props(['title', 'desc'])

<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
    @include('components.navbar')
    <section class="px-28 py-6 pt-20 min-h-screen">
        <!-- Title -->
        <div class="border-gray-200 border-b-2 py-5">
            <h1 class="text-2xl font-semibold">Attendance</h1>
            <p>Manage your work schedule and attendance here.</p>
        </div>
        <content-section class="grid grid-cols-[20%_minmax(20%,_1fr)] gap-10 py-10">
            <!-- Sidebar -->
            <aside class="grid gap-2 h-fit">
                @include('components.sidebar-item', with(['item_title' => 'Take Attendance', 'path' => 'attendance.take-attendance-page']))
                @include('components.sidebar-item', with(['item_title' => 'Input Schedule', 'path' => 'attendance.input-schedule-page']))
                @include('components.sidebar-item', with(['item_title' => 'Report', 'path' => 'attendance.report-page']))
            </aside>
            <section>
                <div class="border-gray-200 border-b-2 pb-5">
                    <h2 class="text-xl font-semibold">{{ $title }}</h2>
                    <p class="text-gray-500">{{ $desc }}</p>
                </div>
                @yield('content')
            </section>
        </content-section>
    </section>
</body>
</html>
