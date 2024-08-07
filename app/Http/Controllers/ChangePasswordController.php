<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function changePassword(Request $request)
    {
        $validated = $request->validated();

        return $this->changePasswordService
            ->changePasswordValidation($validated);

    }
}
