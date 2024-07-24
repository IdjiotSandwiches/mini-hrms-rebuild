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

    public function countdownTimer()
    {
        $userLastUpdate = $this->getUser()
            ->last_password_change;

        $userLastUpdate = $this->convertTime($userLastUpdate);
        $currentTime = $this->convertTime(Carbon::now());

        $countdown = 86400 - $userLastUpdate->diffInSeconds($currentTime);
        $hours = floor($countdown / 3600);
        $minutes = floor($countdown / 60) % 60;
        $seconds = $countdown % 60;

        if ($hours)  return str($hours) . ' hours';
        elseif ($minutes) return str($minutes) . ' minutes';

        return str($seconds) . ' seconds';
    }

    public function changePasswordValidation($validated)
    {
        try {
            DB::beginTransaction();

            if (!$this->isUpdateTime())
            {
                $countdownTimer = $this->countdownTimer();
                DB::rollBack();
                return back()->with([
                    'status' => 'error',
                    'message' => 'You need to wait ' . str($countdownTimer) . ' to change password again.'
                ]);
            }

            if (!Hash::check($validated['confirm_password'], $this->getUser()
                ->getAuthPassword())) {
                    DB::rollBack();
                    return back()->withErrors([
                        'confirm_password' => 'The password confirmation does not match.',
                    ]);
            }

            if (Hash::check($validated['update_password'], $this->getUser()
                ->getAuthPassword())) {
                    DB::rollBack();
                    return back()->withErrors([
                        'update_password' => 'New password cannot be same as current password.',
                    ]);
            }

            $user = $this->getUser();
            $user->password = Hash::make($validated['update_password']);
            $user->last_password_change = $this->convertTime(Carbon::now());
            $user->save();

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
