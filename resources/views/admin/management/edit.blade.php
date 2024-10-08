@extends('admin.layout', with(['title' => 'Users Management', 'desc' => 'Manage registered users.']))
@section('title', 'Admin - Users Management')

@section('content')
    @include('components.loading-overlay')
    <div class="border-gray-200 border-b-2 pb-5">
        <h2 class="text-xl font-semibold">Edit User</h2>
        <p>Edit user properties.</p>
    </div>
    <edit-section class="flex flex-col gap-4 py-10">
        <edit-form>
            <div class="mb-5">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                <input type="text" aria-label="disabled input" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $user->username }}" disabled>
            </div>
            <div class="grid gap-6 mb-5 md:grid-cols-2">
                <div>
                    <label for="first-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First name</label>
                    <input type="text" id="first-name" name="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ $user->firstName }}" />
                </div>
                <div>
                    <label for="last-name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last name</label>
                    <input type="text" id="last-name" name="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ $user->lastName }}" />
                </div>
            </div>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ $user->email }}" />
                <p id="email-error" class="error-msg text-red-500"></p>
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                <p id="password-error" class="error-msg text-red-500"></p>
            </div>
            <button type="submit" id="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </edit-form>
    </edit-section>

    <div class="border-gray-200 border-b-2 pb-5">
        <h2 class="text-xl font-semibold">Delete User</h2>
        <p>Remove user from database.</p>
    </div>
    <delete-section class="flex flex-col gap-4 py-10">
        <delete-form>
            <div class="mb-5">
                <label for="password" class="flex items-center mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Enter current admin password to delete
                </label>
                <input type="password" id="confirmation_password" name="confirmation_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                <p id="confirmation_password-error" class="error-msg text-red-500"></p>
            </div>
            <button id="delete" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-blue-800">Delete</button>
        </delete-form>
    </delete-section>
@endsection

@section('extra-js')
    <script>
        function ajaxRequest(url, type, data) {
            $.ajax({
                type: type,
                url: url,
                data: data,
                beforeSend: function() {
                    $('#loading-overlay').show();
                },
                complete: function() {
                    $('#loading-overlay').hide();
                },
                success:function(response, textStatus, xhr) {
                    alertSwal.fire({
                        title: response.status,
                        text: response.message,
                        icon: response.status,
                    }).then((result) => {
                        if(result.isConfirmed) {
                            window.location.href = '{{ route('admin.management.index') }}';
                        }
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $('.error-msg').text('');
                        $('input').removeClass('border-red-500 dark:border-red-500');

                        $.each(errors, function(key, message) {
                            $(`#${key}`).addClass('border-red-500 dark:border-red-500');
                            $(`#${key}-error`).text(message[0]);
                        });
                    }
                    else {
                        alertSwal.fire({
                            title: response.status,
                            text: response.message,
                            icon: response.status,
                        }).then((result) => {
                            if(result.isConfirmed) {
                                window.location.href = '{{ route('admin.management.index') }}';
                            }
                        });
                    }
                }
            });
        }

        $(document).ready(function () {
            $('#submit').click(function() {
                let data = {
                    first_name: $('#first-name').val(),
                    last_name: $('#last-name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                };
                const URL = '{{ route('admin.management.edit', $user->id) }}';
                const TYPE = 'PUT';

                ajaxRequest(URL, TYPE, data);
            });

            $('#delete').click(function() {
                let password = $('#confirmation_password').val();
                if(!password) return;

                confirmSwal.fire({
                    title: 'Are you sure?',
                    text: 'Once completed, this action cannot be undone.',
                    icon: 'warning',
                    iconColor: 'red',
                }).then((result) => {
                    let data = {
                        confirmation_password: password,
                    };
                    const URL = '{{ route('admin.management.delete', $user->id) }}';
                    const TYPE = 'DELETE';

                    if(result.isConfirmed) ajaxRequest(URL, TYPE, data);
                });
            });
        });
    </script>
@endsection
