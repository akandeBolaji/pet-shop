<?php

namespace App\Http\Services;

use Auth;
use Exception;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Register new admin.
     *
     * @param array $data
     * @throws Exception
     */
    public function registerAdmin(array $data): UserResource
    {
        $user = $this->create($data, true);

        return new UserResource($user);
    }

    /**
     * Register new user.
     *
     * @param array $data
     * @throws Exception
     */
    public function registerUser(array $data): UserResource
    {
        $user = $this->create($data);

        return new UserResource($user);
    }

    /**
     * Create a user record.
     *
     * @param array $data
     *@throws Exception
     */
    private function create(array $data, bool $is_admin = false): User
    {
        $user = new User($data);
        $user->is_admin = $is_admin;
        $user->is_marketing = ! empty($data['is_marketing']);
        $user->password = Hash::make($data['password']);

        if ($user->save()) {
            return $user;
        }

        //throw new UserCouldNotBeCreatedException($user);
        throw new Exception('User could not be created');
    }

    /**
     * Validates admin credentials and returns access token.
     *
     * @param array $credentials
     */
    public function adminLogin(array $credentials): ?string
    {
        $credentials['is_admin'] = true;

        return Auth::attemptLogin($credentials);
    }

    /**
     * Validates user credentials and returns access token.
     *
     * @param array $credentials
     */
    public function userLogin(array $credentials): ?string
    {
        $credentials['is_admin'] = false;

        return Auth::attemptLogin($credentials);
    }

    /**
     * Updates user record.
     *
     * @param array $data
     */
    public function update(User $user, array $data): bool
    {
        $data['is_marketing'] = ! empty($data['is_marketing']);

        return $user->update($data);
    }

    /**
     * Deletes user record.
     */
    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }
}
