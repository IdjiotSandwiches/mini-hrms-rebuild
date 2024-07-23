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
        return view('profile.edit-profile.index');
    }

    public function editProfile(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
        ]);
        dd($validated);
    }
}
