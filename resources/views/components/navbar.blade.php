<nav class="bg-white fixed z-10 w-full flex justify-between items-center border-gray-200 border-b-[0.5px] px-10 h-16 select-none md:px-28 dark:bg-[#121212] dark:text-white">
    @auth
        @can('admin', auth()->user())
            <a class="text-3xl font-semibold dark:text-white" href="{{ route('v1.admin.dashboard.index') }}">hrms.</a>
        @else
            <a class="text-3xl font-semibold dark:text-white" href="{{ route('v1.take-attendance.index') }}">hrms.</a>
        @endcan
        <section id="nav-profile" class="flex items-center gap-4">
            <h1 class="hidden sm:block capitalize">Welcome, <span class="text-blue-400">{{ auth()->user()->name }}</span></h1>
            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold uppercase">
                {{ Str::substr(auth()->user()->name, 0, 1) }}
            </div>
        </section>
    @else
        <a class="text-3xl font-semibold" href="{{ route('login') }}">hrms.</a>
        <nav-button class="md:flex md:gap-2 hidden">
            @include('components.navbar-item', with(['name' => 'Login', 'path' => 'login']))
            @include('components.navbar-item', with(['name' => 'Register', 'path' => 'register']))
        </nav-button>
    @endauth
</nav>

@auth
    <nav-item class="nav-dropdown z-20 fixed hidden bg-white right-0 mt-20 mr-10 w-64 border-2 rounded-md divide-y-2 md:mr-28 dark:bg-[#121212] dark:text-white">
        <div class="py-2 px-4">
            <p class="text-md">{{ auth()->user()->name }}</p>
            <p class="text-xs">{{ auth()->user()->email }}</p>
        </div>
        <div class="select-none grid">
            @can('admin', auth()->user())
                @include('components.dropdown-item', with(['name' => 'Dashboard', 'path' => 'v1.admin.dashboard.index']))
            @endcan
            @can('attendance', auth()->user())
                @include('components.dropdown-item', with(['name' => 'Attendance', 'path' => 'v1.take-attendance.index']))
                @include('components.dropdown-item', with(['name' => 'Profile', 'path' => 'v1.settings.profile.edit']))
            @endcan
        </div>
        <form action="{{ route('logout') }}" method="POST" class="select-none grid">
            @csrf
            <button type="submit" class="py-2 px-4 hover:bg-gray-100 transition-colors text-sm text-left dark:hover:bg-gray-600">Logout</button>
        </form>
    </nav-item>
@endauth
