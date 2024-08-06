<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;
use App\Http\Requests\RegisterRequest;

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

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        return $this->registerService->registerUser($validated);
    }
}
