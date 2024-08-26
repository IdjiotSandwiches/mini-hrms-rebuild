<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Services\Profile\ChangePasswordService;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('profile.change-password.index');
    }

    public function changePassword(ChangePasswordRequest $request, ChangePasswordService $changePasswordService)
    {
        $validated = $request->validated();

        return $changePasswordService->changePasswordValidation($validated);
    }
}
