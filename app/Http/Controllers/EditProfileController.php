<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Profile\EditProfileService;

class EditProfileController extends Controller
{
    private $editProfileService;

    public function __construct()
    {
        $this->editProfileService = new EditProfileService();
    }

    public function index()
    {
        $userInformation = $this->editProfileService
            ->getUserInformation();

        return view('profile.edit-profile.index', with([
            'userInformation' => $userInformation,
        ]));
    }

    public function editProfile(Request $request)
    {
        $validated = $request->validate([
            'avatar' => 'image|extensions:jpg,jpeg,png|nullable',
            'first_name' => 'string|nullable',
            'last_name' => 'string|nullable',
        ]);

        $validated['avatar'] = $request->hasFile('avatar') ?
            $request->file('avatar')->store('avatars') : null;

        return $this->editProfileService->updateProfile($validated);
    }
}
