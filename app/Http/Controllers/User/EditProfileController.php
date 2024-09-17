<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EditProfileRequest;
use App\Services\Profile\EditProfileService;

class EditProfileController extends Controller
{
    public function index(EditProfileService $editProfileService)
    {
        $userInformation = $editProfileService->getUserInformation();

        return view('profile.edit-profile.index', with([
            'userInformation' => $userInformation,
        ]));
    }

    public function editProfile(EditProfileRequest $request, EditProfileService $editProfileService)
    {
        $validated = $request->validated();

        $validated['avatar'] = $request->hasFile('avatar') ?
            'storage/' . $request->file('avatar')->store('avatars') : null;

        return $editProfileService->updateProfile($validated);
    }
}
