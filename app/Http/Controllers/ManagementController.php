<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\ManagementService;

class ManagementController extends Controller
{
    public function index(ManagementService $managementService)
    {
        $users = new User;
        $users = $managementService->getUserList($users);

        return view('admin.management.index', with([
            'users' => $users,
        ]));
    }

    public function showEditPage(Request $request, ManagementService $managementService)
    {
        try {
            $username = $request->username;
            $user = $managementService->getCurrentUser($username);

            return view('admin.management.edit', with([
                'user' => $user,
            ]));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function edit(Request $request)
    {

    }

    // search using ajax request
    public function search(Request $request, ManagementService $managementService)
    {
        if (!$request->ajax()) abort(404);

        $keyword = $request->keyword;
        $users = $managementService->searchUserList($keyword);

        return response()->json([
            'message' => 'Search Done',
            'data' => $users,
        ], 200);
    }
}
