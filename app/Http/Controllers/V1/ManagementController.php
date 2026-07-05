<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\EditUserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\ManagementException;
use App\Services\Admin\ManagementService;

class ManagementController extends Controller
{
    private ManagementService $service;

    public function __construct(ManagementService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $keyword = $request->string('search');
        $users = $this->service->getUsers($keyword);

        return view('admin.management.index', with([
            'users' => $users,
        ]));
    }

    public function edit(string $id)
    {
        try {
            $user = $this->service->getUser($id);
            return view('admin.management.edit', with([
                'user' => $user,
            ]));
        } catch (ManagementException $e) {
            return back()->with([
                'status' => 'warning',
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }

    public function update(string $id, EditUserRequest $request)
    {
        try {
            $data = $request->validated();
            $this->service->update($id, $data);

            return back()->with([
                'status' => 'success',
                'message' => 'User updated.',
            ]);
        } catch (ManagementException $e) {
            return back()->with([
                'status' => 'warning',
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }

    public function destroy(string $id, DeleteUserRequest $request)
    {
        try {
            $data = $request->validated();
            $this->service->destroy($id, $data['password']);

            return to_route('v1.admin.management.index')->with([
                'status' => 'success',
                'message' => 'User deleted.',
            ]);
        } catch (ManagementException $e) {
            return back()->with([
                'status' => 'warning',
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }
}
