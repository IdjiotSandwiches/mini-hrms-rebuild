<?php

namespace App\Http\Controllers;

use App\Services\Admin\ManagementService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagementController extends Controller
{
    public function index(ManagementService $managementService)
    {
        $users = $managementService->getUserList();

        return view('admin.management.index', with([
            'users' => $users,
        ]));
    }

    public function editPage($username)
    {
        // dd($username);
        return view('admin.management.edit');
    }

    public function edit($username)
    {

    }

    // search using ajax request
    public function search(Request $request)
    {
        if (!$request->ajax()) return 404;
    }
}
