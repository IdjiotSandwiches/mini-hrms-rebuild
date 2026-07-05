<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ManagementException;

class ManagementService extends BaseService
{
    /**
     * @param string $keyword
     * @return \Illuminate\Pagination\LengthAwarePaginator<int, User>
     */
    public function getUsers(string $keyword): \Illuminate\Pagination\LengthAwarePaginator
    {
        $users = User::when($keyword,
                fn($q) =>  $q->whereFullText(['name', 'email'], $keyword)
            )->paginate(10, ['*'], 'user');

        return $users;
    }

    /**
     * @param string $id
     * @return User|\stdClass|null
     */
    public function getUser(string $id): User|\stdClass|null
    {
        $user = User::where('uuid', $id)
            ->first();

        if (!$user) {
            throw ManagementException::userNotFound();
        }

        return $user;
    }

    /**
     * @param string $id
     * @param array $validated
     * @return mixed
     */
    public function update(string $id, array $validated): mixed
    {
        return DB::transaction(function () use ($id, $validated) {
            $user = $this->getUser($id);

            $user->name     = $validated['name'] ?: $user->name;
            $user->email    = $validated['email'] ?: $user->email;

            if (!empty($validated['password'])) {
                $user->password = $validated['password'];
            }

            $user->save();
        });
    }

    /**
     * @param string $id
     * @param string $password
     * @return mixed
     */
    public function destroy(string $id, string $password): mixed
    {
        return DB::transaction(function () use ($id, $password) {
            if (!Hash::check($password, $this->getAuthUser()->password)) {
                throw ManagementException::adminPasswordNotMatch();
            }

            $user = $this->getUser($id);
            if ($user->role === RoleEnum::ADMIN) {
                throw ManagementException::cannotRemoveAdmin();
            }

            $user->delete();
        });
    }
}
