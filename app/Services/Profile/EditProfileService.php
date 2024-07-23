<?php

namespace App\Services\Profile;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class EditProfileService extends BaseService
{
    public function updateProfile($validated)
    {
        try {
            DB::beginTransaction();

            if (is_null($validated['avatar']) &&
                is_null($validated['first_name']) &&
                is_null($validated['last_name'])) {
                    DB::rollBack();
                    return back()->with([
                        'status' => 'error',
                        'message' => 'At least one of the fields is required.'
                    ]);
                }

            $user = $this->getUser();
            $user->avatar = $validated['avatar'] ?: $user->avatar;
            $user->first_name = $validated['first_name'] ?: $user->first_name;
            $user->last_name = $validated['last_name'] ?: $user->last_name;
            $user->save();

            DB::commit();
            return back()->with([
                'status' => 'success',
                'message' => 'Your profile has been updated successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }
}
