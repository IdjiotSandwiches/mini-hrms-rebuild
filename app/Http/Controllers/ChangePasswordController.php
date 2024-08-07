<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Services\Profile\ChangePasswordService;

class ChangePasswordController extends Controller
{
    private $changePasswordService;

    public function __construct()
    {
        $this->changePasswordService = new ChangePasswordService();
    }

    public function index()
    {
        return view('profile.change-password.index');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();

        return $this->changePasswordService
            ->changePasswordValidation($validated);

    }
}
