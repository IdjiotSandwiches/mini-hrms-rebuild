<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditUserRequest;
use App\Http\Requests\Admin\DeleteUserRequest;
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
        $id = $request->id;

        if (!User::where('uuid', $id)->first())
            return redirect()->route('admin.management.index');

        $user = $managementService->getCurrentUser($id);

        return view('admin.management.edit', with([
            'user' => $user,
        ]));
    }

    public function search(Request $request, ManagementService $managementService)
    {
        if (!$request->ajax()) abort(404);

        $keyword = $request->keyword;
        $users = $managementService->searchUserList($keyword);

        return response()->json([
            'status' => 'success',
            'message' => 'Search Done',
            'data' => $users,
        ]);
    }

    public function edit(EditUserRequest $request, ManagementService $managementService)
    {
        $validated = $request->validated();
        $id = $request->id;

        return $managementService->editUser($id, $validated);
    }

    public function delete(DeleteUserRequest $request, ManagementService $managementService)
    {
        $validated = $request->validated();
        $id = $request->id;

        return $managementService->deleteUser($id, $validated);
    }
}
