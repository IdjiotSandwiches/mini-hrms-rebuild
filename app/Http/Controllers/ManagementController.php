<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
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
            'message' => 'Search Done',
            'data' => $users,
        ], 200);
    }

    // Nambahin error msg & benerin lgi yg krng
    public function edit(EditUserRequest $request, ManagementService $managementService)
    {
        $validated = $request->validated();
        $id = $request->id;

        $response = $managementService->editUser($id, $validated);
        return redirect()->route('admin.management.index');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id);
        $user->delete();

        return redirect()->route('admin.management.index');
    }
}
