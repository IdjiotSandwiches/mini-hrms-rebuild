<?php

namespace App\Http\Controllers;

use App\Interfaces\StatusInterface;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\LoginService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller implements
    StatusInterface
{
    public function index()
    {
        return view('login');
    }

    public function login(LoginRequest $request, LoginService $loginService): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $response = $loginService->attemptLogin($validated);

            if (!$response) {
                DB::rollBack();
                return back()->with([
                    'status' => self::STATUS_ERROR,
                    'message' => 'E-mail or password invalid'
                ]);
            }

            $response['user']->last_login = $loginService->convertTime(Carbon::now());
            $response['user']->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => self::STATUS_ERROR,
                'message' => 'Invalid operation.'
            ];

            return back()->with($response);
        }

        Auth::guard($response['isAdmin'])->login($response['user']);
        $request->session()->regenerate();

        $route = $response['isAdmin'] == 'admin' ? 'admin.dashboard' : 'attendance.take-attendance-page';
        return redirect()->route($route)
            ->with([
                'status' => self::STATUS_SUCCESS,
                'message' => 'Logged In'
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $response = [
            'status' => self::STATUS_SUCCESS,
            'message' => 'Logged out.'
        ];

        return redirect()->route('landing-page')
            ->with($response);
    }
}
