<?php

namespace App\Services\Profile;

use Carbon\Carbon;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\StatusInterface;

class ChangePasswordService extends BaseService implements
    StatusInterface
{
    /**
     * @return bool
     */
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

    /**
     * @return string
     */
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

        if ($hours)  return "$hours hours";
        elseif ($minutes) return "$minutes minutes";

        return "$seconds seconds";
    }

    /**
     * @param array
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePasswordValidation($validated)
    {
        try {
            DB::beginTransaction();

            if (!$this->isUpdateTime())
            {
                $countdownTimer = $this->countdownTimer();
                DB::rollBack();
                $response = [
                    'status' => self::STATUS_ERROR,
                    'message' => "You need to wait $countdownTimer to change password again."
                ];

                return back()->with($response);
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
            $response = [
                'status' => self::STATUS_SUCCESS,
                'message' => 'Password has been changed successfully.',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => self::STATUS_ERROR,
                'message' => 'Invalid operation.'
            ];

            return back()->with($response);
        }

        return redirect()
            ->intended(route('profile.edit-profile-page'))
            ->with($response);
    }
}
