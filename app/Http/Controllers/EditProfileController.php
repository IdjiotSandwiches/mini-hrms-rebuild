<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProfileRequest;
use App\Services\Profile\EditProfileService;

class EditProfileController extends Controller
{
    private $editProfileService;

    public function __construct()
    {
        $this->editProfileService = new EditProfileService();
    }

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
