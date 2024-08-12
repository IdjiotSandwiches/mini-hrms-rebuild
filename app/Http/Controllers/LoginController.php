<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return back()->with([
                'status' => 'error',
                'message' => 'E-mail or password invalid'
            ]);
        }

        $request->session()->regenerate();
        return redirect()->intended(route('attendance.take-attendance-page'))
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
        $response = [
            'status' => 'success',
            'message' => 'Logged out.'
        ];

        return redirect()->route('landing-page')
            ->with($response);
    }
}
