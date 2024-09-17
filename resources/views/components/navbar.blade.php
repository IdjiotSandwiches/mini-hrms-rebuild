<nav class="
    bg-white fixed z-10 w-full flex justify-between items-center border-gray-200 border-b-[0.5px] px-10 h-16 select-none
    md:px-28
    dark:bg-[#121212] dark:text-white
">
    @auth
        <a class="
            text-3xl font-semibold
            dark:text-white
        " href="">hrms.</a>
        <section class="profile flex items-center gap-4">
            <h1 class="
                hidden
                sm:block
            ">Welcome, <span class="text-blue-400">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span></h1>
            <img src="{{ asset(auth()->user()->avatar) }}" class="h-10 w-10 rounded-full"/>
        </section>
    @else
        <a class="text-3xl font-semibold" href="{{ route('landing-page') }}">hrms.</a>
        <nav-button class="
            md:flex md:gap-2
            hidden
        ">
            @include('components.navbar-item', with(['name' => 'Login', 'path' => ['login', 'landing-page']]))
            @include('components.navbar-item', with(['name' => 'Register', 'path' => ['register']]))
        </nav-button>
    @endauth
</nav>

@auth
    <nav-item class="
        nav-dropdown z-20 fixed hidden bg-white right-0 mt-20 mr-10 w-64 border-2 rounded-md divide-y-2
        md:mr-28
        dark:bg-[#121212] dark:text-white
    ">
        <div class="py-2 px-4">
            <p class="text-md">{{ auth()->user()->username }}</p>
            <p class="text-xs">{{ auth()->user()->email }}</p>
        </div>
        <div class="select-none grid">
            @if (auth()->guard('admin')->check())
                @include('components.dropdown-item', with(['name' => 'Dashboard', 'path' => 'admin.dashboard']))
                @include('components.dropdown-item', with(['name' => 'Edit User', 'path' => 'admin.management.index']))
            @else
                @include('components.dropdown-item', with(['name' => 'Profile', 'path' => 'profile.edit-profile-page']))
                @include('components.dropdown-item', with(['name' => 'Attendance', 'path' => 'attendance.take-attendance-page']))
            @endif
        </div>
        <div class="select-none grid">
            <a href="{{ route('logout') }}" class="
                py-2 px-4 hover:bg-gray-100 transition-colors text-sm
                dark:hover:bg-gray-600
            ">
                Logout
            </a>
        </div>
    </nav-item>
@endauth

<script type="module">
    $(document).ready(function() {
        $('.profile').click(function(event) {
            $('.nav-dropdown').slideToggle('hidden');
            event.stopPropagation();
        });

        $(document).on('click', function(event) {
            let trigger = $('.nav-dropdown');
            let navItem = $(event.target).closest('nav-item');

            if(trigger[0] !== navItem[0]){
                trigger.slideUp('fast');
            }
        });
    });
</script>
