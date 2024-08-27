<?php

namespace App\Http\Controllers;

use App\Services\LoginService;
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

    public function login(LoginRequest $request, LoginService $loginService): RedirectResponse
    {
        $validated = $request->validated();

        $response = $loginService->attemptLogin($validated);

        if (!$response) {
            return back()->with([
                'status' => 'error',
                'message' => 'E-mail or password invalid'
            ]);
        }

        Auth::guard($response['isAdmin'])->login($response['user']);
        $request->session()->regenerate();

        $route = $response['isAdmin'] == 'admin' ? 'welcome' : 'attendance.take-attendance-page';
        return redirect()->route($route)
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
