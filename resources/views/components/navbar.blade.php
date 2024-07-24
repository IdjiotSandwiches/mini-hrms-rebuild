<nav class="bg-white fixed z-10 w-full flex justify-between items-center border-gray-200 border-b-[0.5px] px-28 h-16 select-none">
    @if (auth()->user())
        <a class="text-3xl font-semibold" href="">hrms.</a>
        <section class="profile flex items-center gap-4">
            <h1>Welcome, <span class="text-blue-400">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span></h1>
            <img src="{{ asset(auth()->user()->avatar) }}" class="h-10 w-10 rounded-full bg-blue-400"/>
        </section>
    @else
        <a class="text-3xl font-semibold" href="{{ route('landing-page') }}">hrms.</a>
        <nav-button class="flex gap-2">
            @include('components.navbar-item', with(['name' => 'Login', 'path' => ['login', 'landing-page']]))
            @include('components.navbar-item', with(['name' => 'Register', 'path' => ['register']]))
        </nav-button>
    @endif
</nav>

@if (auth()->user())
    <nav-item class="nav-dropdown z-20 fixed hidden bg-white right-0 mt-20 mr-28 w-64 border-2 rounded-md divide-y-2">
        <div class="py-2 px-4">
            <p class="text-md">{{ auth()->user()->username }}</p>
            <p class="text-xs">{{ auth()->user()->email }}</p>
        </div>
        <div class="select-none grid">
            <a href="{{ route('profile.edit-profile-page') }}" class="py-2 px-4 hover:bg-gray-100 transition-colors text-sm">
                Profile
            </a>
            <a href="{{ route('attendance.take-attendance-page') }}" class="py-2 px-4 hover:bg-gray-100 transition-colors text-sm">
                Attendance
            </a>
        </div>
        <div class="select-none grid">
            <a href="{{ route('logout') }}" class="py-2 px-4 hover:bg-gray-100 transition-colors text-sm">
                Logout
            </a>
        </div>
    </nav-item>
@endif

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
