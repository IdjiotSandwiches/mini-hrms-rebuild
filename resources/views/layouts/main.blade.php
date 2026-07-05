@props(['sidebar' => true])

<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @vite(['resources/css/app.css'])

    <script>
        if (
            localStorage.getItem('appearance') === 'dark'
            || (!('appearance' in localStorage)
            && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}" type="image/x-icon">

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased">
    @include('components.navbar')
    @include('components.toggle-darkmode')
    @include('components.loading-overlay')
    <section class="px-10 py-6 pt-20 min-h-screen md:px-28 dark:bg-[#121212] dark:text-white">
        @yield('main-title')

        <content-section class="lg:grid lg:grid-cols-[20%_minmax(20%,1fr)] lg:gap-10 lg:py-10 flex flex-col gap-4 py-5">
            <!-- Sidebar -->
            <aside class="grid gap-2 h-fit">
                @if ($sidebar)
                    @can('admin', auth()->user())
                        @include('components.sidebar-item', with(['item_title' => 'Dashboard', 'path' => ['v1.admin.dashboard.index']]))
                        @include('components.sidebar-item', with(['item_title' => 'Edit User', 'path' => ['v1.admin.management.index', 'v1.admin.management.edit']]))
                    @endcan
                    @can('attendance', auth()->user())
                        @include('components.sidebar-item', with(['item_title' => 'Take Attendance', 'path' => ['v1.take-attendance.index']]))
                        @include('components.sidebar-item', with(['item_title' => 'Input Schedule', 'path' => ['v1.input-schedule.index', 'v1.input-schedule.edit']]))
                        @include('components.sidebar-item', with(['item_title' => 'Report', 'path' => ['v1.report.index']]))
                    @endcan
                @else
                    @yield('sidebar')
                @endif
            </aside>

            <section>
                @yield('sub-title')
                @yield('content')
            </section>

            @can('admin', auth()->user())
                <a class="fixed bottom-5 left-1/2 -translate-x-1/2 -translate-y-1/2" href="{{ route('v2.admin.dashboard.index') }}">
                    <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-3 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 has-[>svg]:px-3 shadow-lg/30">
                        Go to Newest Version
                    </button>
                </a>
            @else
                <a class="fixed bottom-5 left-1/2 -translate-x-1/2 -translate-y-1/2" href="{{ route('v2.take-attendance.index') }}">
                    <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-3 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 has-[>svg]:px-3 shadow-lg/30">
                        Go to Newest Version
                    </button>
                </a>
            @endcan
        </content-section>
    </section>

    @include('components.common-js')
    @yield('extra-js')
</body>
</html>
