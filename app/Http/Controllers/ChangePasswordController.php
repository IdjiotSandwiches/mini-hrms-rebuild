<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('profile.change-password.index');
    }
}
