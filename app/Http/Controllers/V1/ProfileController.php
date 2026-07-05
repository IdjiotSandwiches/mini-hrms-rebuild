<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit-profile.index');
    }

    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('v1.settings.profile.edit')->with([
            'status' => 'success',
            'message' => 'Profile updated.',
        ]);
    }
}
