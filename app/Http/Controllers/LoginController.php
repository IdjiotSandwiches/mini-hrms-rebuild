<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validated)) {
            $request->session()->regenerate();
            return back()
                ->withErrors([
                    'email' => ' ',
                    'password' => ' ',
                ])
                ->with([
                    'status' => 'error',
                    'message' => 'E-mail or password invalid'
                ]);
            }

        return redirect()
            ->intended(route('attendance.take-attendance-page'))
            ->with([
                'status' => 'success',
                'message' => 'Logged In'
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing-page');
    }
}
