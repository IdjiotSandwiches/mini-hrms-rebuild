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
        $validated = $request->validate([
            'update_password' => 'required|min:6',
            'confirm_password' => 'required',
        ]);

        return $this->changePasswordService
            ->changePasswordValidation($validated);

    }
}
