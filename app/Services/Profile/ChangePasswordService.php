<?php

namespace App\Services\Profile;

use Carbon\Carbon;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordService extends BaseService
{
    public function isUpdateTime()
    {
        $userLastUpdate = $this->getUser()
            ->last_password_change;

        if (!$userLastUpdate) return true;

        $userLastUpdate = $this->convertTime($userLastUpdate);
        $currentTime = $this->convertTime(Carbon::now());

        if ($userLastUpdate->diffInSeconds($currentTime) >= 86400) return true;
        return false;
    }

    public function timerCountdown()
    {
        $userLastUpdate = $this->getUser()
            ->last_password_change;

        $userLastUpdate = $this->convertTime($userLastUpdate);
        $currentTime = $this->convertTime(Carbon::now());

        $countdown = 86400 - $userLastUpdate->diffInSeconds($currentTime);
        return $countdown;
    }

    public function changePasswordValidation($validated)
    {
        try {
            DB::beginTransaction();

            if (!Hash::check($validated['confirm_password'], $this->getUser()
                ->getAuthPassword())) {
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

            if (Hash::check($validated['update_password'], $this->getUser()
                ->getAuthPassword())) {
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
                    'last_password_change' => $this->convertTime(Carbon::now()),
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
