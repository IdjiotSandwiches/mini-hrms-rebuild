<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email:dns|unique:users,email',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ]);

        $user = new User();
        $user->email = $validated['email'];
        $user->first_name = ucwords($validated['first_name']);
        $user->last_name = ucwords($validated['last_name']);
        $user->password = Hash::make($validated['password']);

        $username = strtolower($validated['first_name']) . strtolower($validated['last_name']);
        $suffix = 0;
        while(User::where('username', $username)->exists()) {
            $suffix++;
            $username .= $suffix;
        }

        $user->username = $username;
        $user->save();
        return redirect()->route('login')->with([
            'status' => 'success',
            'message' => 'Account successfully created',
        ]);
    }
}
