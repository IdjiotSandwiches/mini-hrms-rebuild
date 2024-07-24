<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RegisterService;

class RegisterController extends Controller
{
    private $registerService;

    public function __construct()
    {
        $this->registerService = new RegisterService();
    }

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

        return $this->registerService->registerUser($validated);
    }
}
