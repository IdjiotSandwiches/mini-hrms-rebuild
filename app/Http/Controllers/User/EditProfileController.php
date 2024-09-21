<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EditProfileRequest;
use App\Interfaces\UserInterface;
use App\Services\Profile\EditProfileService;

class EditProfileController extends Controller implements
    UserInterface
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

        $validated[self::AVATAR_COLUMN] = $request->hasFile(self::AVATAR_COLUMN) ?
            'storage/' . $request->file(self::AVATAR_COLUMN)->store('avatars') : null;

        return $editProfileService->updateProfile($validated);
    }
}
