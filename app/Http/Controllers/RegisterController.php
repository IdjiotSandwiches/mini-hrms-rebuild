<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function register(RegisterRequest $request, RegisterService $registerService)
    {
        $validated = $request->validated();

        return $registerService->registerUser($validated);
    }
}
