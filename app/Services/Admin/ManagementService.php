<?php

namespace App\Services\Admin;

use App\Enums\RoleEnum;
use App\Exceptions\ManagementException;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagementService extends BaseService
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function getUsers(string $keyword): LengthAwarePaginator
    {
        $users = User::when($keyword,
            fn ($q) => $q->whereFullText(['name', 'email'], $keyword)
        )->paginate(10, ['*'], 'user');

        return $users;
    }

    public function getUser(string $id): User|\stdClass|null
    {
        $user = User::where('uuid', $id)
            ->first();

        if (! $user) {
            throw ManagementException::userNotFound();
        }

        return $user;
    }

    public function update(string $id, array $validated): mixed
    {
        return DB::transaction(function () use ($id, $validated) {
            $user = $this->getUser($id);

            $user->name = $validated['name'] ?: $user->name;
            $user->email = $validated['email'] ?: $user->email;

            if (! empty($validated['password'])) {
                $user->password = $validated['password'];
            }

            $user->save();
        });
    }

    public function destroy(string $id, string $password): mixed
    {
        return DB::transaction(function () use ($id, $password) {
            if (! Hash::check($password, $this->getAuthUser()->password)) {
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
