<nav class="bg-white fixed z-10 w-full flex justify-between items-center border-gray-200 border-b-[0.5px] px-28 h-16 select-none">
    @if (auth()->user())
        <a class="text-3xl font-semibold" href="">hrms.</a>
        <section class="profile flex items-center gap-5">
            <h1>Welcome, <span class="text-blue-400">XXX</span></h1>
            <div class="h-8 w-8 rounded-full bg-blue-400"></div>
        </section>
    @else
        <a class="text-3xl font-semibold" href="/">hrms.</a>
        <nav-button class="flex gap-2">
            @include('components.navbar-item', with(['name' => 'Login', 'route' => route('login-page'), 'path' => ['/', 'login']]))
            @include('components.navbar-item', with(['name' => 'Register', 'route' => route('register-page'), 'path' => 'register']))
        </nav-button>
    @endif
</nav>

<nav-item class="nav-dropdown z-20 absolute hidden bg-white right-0 mt-20 mr-28 w-56 border-2 rounded-md divide-y-2">
    <div class="py-2 px-4">
        <p class="text-md">User</p>
        <p class="text-sm">user@email.com</p>
    </div>
    <div class="select-none">
        <div class="py-2 px-4 hover:bg-gray-100 transition-colors">
            <a href="" class="text-sm">Profile</a>
        </div>
        <div class="py-2 px-4 hover:bg-gray-100 transition-colors">
            <a href="" class="text-sm">Attendance</a>
        </div>
    </div>
    <div class="select-none py-2 px-4 hover:bg-gray-100 transition-colors">
        <a href="" class="text-sm">Logout</a>
    </div>
</nav-item>

<script type="module">
    $(document).ready(function() {
        $('.profile').click(function() {
            $('.nav-dropdown').slideToggle('hidden');
        });
    });
    $(document).on('click', function(event){
        let $trigger = $('.profile');
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $('.nav-dropdown').slideUp('fast');
        }
    });
</script>
