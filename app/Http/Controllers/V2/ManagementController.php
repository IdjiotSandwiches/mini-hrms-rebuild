<?php

namespace App\Http\Controllers\V2;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use App\Exceptions\ManagementException;
use App\Http\Requests\DeleteUserRequest;
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

        return Inertia::render('admin/UserManagement/Index', [
            'users' => $this->service->getUsers($keyword)
        ]);
    }

    public function edit(string $id)
    {
        try {
            $user = $this->service->getUser($id);
            return Inertia::render('admin/UserManagement/Edit', [
                'user' => $user
            ]);
        } catch (ManagementException $e) {
            Inertia::flash('toast', ['type' => 'warning', 'message' => __($e->getMessage())]);
            return to_route('v2.admin.management.index');
        } catch (\Exception $e) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('An error has occurred while saving the data. Please try again.')]);
            return to_route('v2.admin.management.index');
        }
    }

    public function update(string $id, EditUserRequest $request)
    {
        try {
            $data = $request->validated();
            $this->service->update($id, $data);

            return Inertia::flash(
                'toast', ['type' => 'success', 'message' => __('User updated.')]
            )->location(route('v2.admin.management.index'));
        } catch (ManagementException $e) {
            return Inertia::flash(
                'toast', ['type' => 'warning', 'message' => __($e->getMessage())]
            )->back();
        } catch (\Exception $e) {
            return Inertia::flash(
                'toast', ['type' => 'error', 'message' => __('An error has occurred while saving the data. Please try again.')]
            )->back();
        }
    }

    public function destroy(string $id, DeleteUserRequest $request)
    {
        try {
            $data = $request->validated();
            $this->service->destroy($id, $data['password']);

            return Inertia::flash(
                'toast', ['type' => 'success', 'message' => __('User deleted.')]
            )->location(route('v2.admin.management.index'));
        } catch (ManagementException $e) {
            return Inertia::flash(
                'toast', ['type' => 'warning', 'message' => __($e->getMessage())]
            )->back();
        } catch (\Exception $e) {
            return Inertia::flash(
                'toast', ['type' => 'error', 'message' => __('An error has occurred while saving the data. Please try again.')]
            )->back();
        }
    }
}
