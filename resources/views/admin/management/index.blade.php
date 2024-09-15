@extends('admin.layout', with(['title' => 'Users Management', 'desc' => 'Manage registered users.']))
@section('title', 'Admin - Users Management')

@section('content')
    <management-section class="flex flex-col gap-8 pb-10">
        <search-bar class="gap-4 flex flex-col">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search" />
                <button id="submit" type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
            </div>
        </search-bar>
        <user-list class="gap-4 flex flex-col">
            <div class="relative overflow-x-auto sm:rounded-lg">
                <table id="user-list" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                First Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Last Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Username
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $user->firstName }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->lastName }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->username }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.management.edit-page', $user->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links('pagination::tailwind') }}
        </user-list>
    </management-section>
@endsection

@section('extra-js')
    <script>
        function ajaxRequest() {
            const keyword = $('#default-search').val();
            let url = '{{ route('admin.management.search', ['::KEYWORD::']) }}'
            url = url.replace('::KEYWORD::', encodeURIComponent(keyword));

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response, textStatus, xhr) {
                    const users = response.data.data;
                    const table = $('#user-list').find('tbody');
                    table.children().remove();
                    console.log(users)

                    if(users.length === 0) {
                        let row = `
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td colspan="5" class="px-6 py-4 text-center">
                                    List empty.
                                </td>
                            </tr>
                        `;
                        table.append(row);
                    }
                    else {
                        $.each(users, function(index, user) {
                            let route = "{{ route('admin.management.edit-page', ['::USERKEYWORD::']) }}";
                            route = route.replace('::USERKEYWORD::', encodeURIComponent(user.username));

                            let row = `
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">${user.firstName}</th>
                                    <td class="px-6 py-4">${user.lastName}</td>
                                    <td class="px-6 py-4">${user.username}</td>
                                    <td class="px-6 py-4">${user.email}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="${route}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            `;
                            table.append(row);
                        });
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        }

        $(document).ready(function() {
            $('#submit').click(function() {
                ajaxRequest();
            });

            $('#default-search').keypress(function(e) {
                if(e.which == 13) ajaxRequest();
            });
        });

    </script>
@endsection
