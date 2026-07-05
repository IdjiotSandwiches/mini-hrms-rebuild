@extends('admin.admin-layout', with(['title' => 'Users Management', 'desc' => 'Manage registered users.']))
@section('title', 'Admin - Users Management')

@section('content')
    <div class="border-gray-200 border-b-2 pb-5">
        <h2 class="text-xl font-semibold">Edit User</h2>
        <p>Edit user properties.</p>
    </div>
    <edit-section class="flex flex-col gap-4 py-10">
        <form action="{{ route('v1.admin.management.update', $user->uuid) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label for="uuid" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID</label>
                <input type="text" aria-label="disabled input" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 disabled:text-muted-foreground" value="{{ $user->uuid }}" disabled>
            </div>
            <div class="mb-5">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <input type="text" id="name" name="name" @class([
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'border-red-500 dark:border-red-500' => $errors->has('name'),
                ]) placeholder="{{ $user->name }}" />
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="email" name="email" @class([
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'border-red-500 dark:border-red-500' => $errors->has('email'),
                ]) placeholder="{{ $user->email }}" />
                @error('email')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-5">
                <label for="edit-password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" id="edit-password" name="password" @class([
                    'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'border-red-500 dark:border-red-500' => $errors->update->has('password'),
                ]) />
                @error('password', 'update')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="disabled:bg-blue-400 disabled:dark:bg-blue-500 disabled:cursor-not-allowed text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
        </form>
    </edit-section>

    @if ($user->role !== \App\Enums\RoleEnum::ADMIN)
        <div class="border-gray-200 border-b-2 pb-5">
            <h2 class="text-xl font-semibold">Delete User</h2>
            <p>Remove user from database.</p>
        </div>
        <delete-section class="flex flex-col gap-4 py-10">
            <form action="{{ route('v1.admin.management.destroy', $user->uuid) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-5">
                    <label for="admin-password" class="flex items-center mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Enter current admin password to delete
                    </label>
                    <input type="password" id="admin-password" name="password" @class([
                        'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                        'border-red-500 dark:border-red-500' => $errors->delete->has('password')
                    ]) />
                    @error('password', 'delete')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="disabled:bg-red-400 disabled:dark:bg-red-500 disabled:cursor-not-allowed text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-blue-800">Delete</button>
            </form>
        </delete-section>
    @endif
@endsection
