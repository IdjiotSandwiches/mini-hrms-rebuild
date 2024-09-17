<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
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
