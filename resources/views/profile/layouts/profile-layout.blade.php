@props(['title', 'desc'])

<!DOCTYPE html>
<html lang="en" class="">
@include('layouts.head')
<body>
    @include('components.navbar')
    @include('components.toggle-darkmode')
    <section class="
        px-10 py-6 pt-20 min-h-screen
        md:px-28
        dark:bg-[#121212] dark:text-white
    ">
        <!-- Title -->
        <div class="border-gray-200 border-b-2 py-5">
            <h1 class="text-2xl font-semibold">Profile</h1>
            <p>Manage your account settings here.</p>
        </div>
        <content-section class="
            lg:grid lg:grid-cols-[20%_minmax(20%,_1fr)] lg:gap-10 lg:py-10
            flex flex-col gap-4 py-5
        ">
            <!-- Sidebar -->
            <aside class="grid gap-2 h-fit">
                @include('components.sidebar-item', with(['item_title' => 'Edit Profile', 'path' => ['profile.edit-profile-page']]))
                @include('components.sidebar-item', with(['item_title' => 'Change Password', 'path' => ['profile.change-password-page']]))
            </aside>
            <section>
                <div class="border-gray-200 border-b-2 pb-5">
                    <h2 class="text-xl font-semibold">{{ $title }}</h2>
                    <p>{{ $desc }}</p>
                </div>
                @yield('content')
            </section>
        </content-section>
    </section>

    @include('components.common-js')
    @yield('extra-js')
</body>
</html>
