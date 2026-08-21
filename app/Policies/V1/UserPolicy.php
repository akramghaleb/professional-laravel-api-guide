<?php

namespace App\Policies\V1;

use App\Models\User;
use App\Permissions\V1\Abilities;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can delete the given user account.
     */
    public function delete(User $user, User $model)
    {
        return $user->tokenCan(Abilities::DeleteUser);
    }

    /**
     * Determine whether the user can replace the given user account.
     */
    public function replace(User $user, User $model)
    {
        return $user->tokenCan(Abilities::ReplaceUser);
    }

    /**
     * Determine whether the user can create the given user account.
     */
    public function store(User $user)
    {
        return $user->tokenCan(Abilities::CreateUser);
    }

    /**
     * Determine whether the user can update the given user account.
     */
    public function update(User $user, User $model)
    {
        return $user->tokenCan(Abilities::UpdateUser);
    }
}
