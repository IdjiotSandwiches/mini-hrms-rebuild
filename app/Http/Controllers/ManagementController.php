<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagementController extends Controller
{
    public function index()
    {
        $users = User::paginate(2, ['*'], 'user')
            ->through(function ($user) {
                $result = [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'username' => $user->username,
                    'email' => $user->email,
                ];

                return (object) $result;
            });
        return view('admin.management.index', with([
            'users' => $users,
        ]));
    }

    // search using ajax request
    public function search(Request $request)
    {
        if (!$request->ajax()) return 404;
    }
}
