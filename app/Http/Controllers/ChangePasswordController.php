<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
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

        try {
            DB::beginTransaction();

            if (!Hash::check($validated['confirm_password'], auth()->user()->getAuthPassword())) {
                DB::rollBack();
                return back()->withErrors([
                        'update_password' => ' ',
                        'confirm_password' => ' ',
                    ])
                    ->with([
                        'status' => 'error',
                        'message' => 'The password confirmation does not match.'
                    ]);
            }

            if (Hash::check($validated['update_password'], auth()->user()->getAuthPassword())) {
                DB::rollBack();
                return back()->withErrors([
                        'update_password' => ' ',
                        'confirm_password' => ' ',
                    ])
                    ->with([
                        'status' => 'error',
                        'message' => 'New password cannot be same as current password.'
                    ]);
            }

            User::where('user_id', auth()->user()->user_id)
                ->update([
                    'password' => Hash::make($validated['update_password']),
                ]);

            DB::commit();
            return redirect()
                ->intended(route('profile.edit-profile-page'))
                ->with([
                    'status' => 'success',
                    'message' => 'Password has been changed successfully.',
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.'
            ]);
        }
    }
}
