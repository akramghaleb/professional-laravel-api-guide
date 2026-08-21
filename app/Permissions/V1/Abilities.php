<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities
{
    public const CreateOrder = 'order:create';
    public const UpdateOrder = 'order:update';
    public const ReplaceOrder = 'order:replace';
    public const DeleteOrder = 'order:delete';

    public const CreateOwnOrder = 'order:own:create';
    public const UpdateOwnOrder = 'order:own:update';
    public const DeleteOwnOrder = 'order:own:delete';

    public const CreateUser = 'user:create';
    public const UpdateUser = 'user:update';
    public const ReplaceUser = 'user:replace';
    public const DeleteUser = 'user:delete';

    /**
     * Get the token abilities granted to the given user.
     */
    public static function getAbilities(User $user)
    {
        // don't assign '*'
        if ($user->is_manager) {
            return [
                self::CreateOrder,
                self::UpdateOrder,
                self::ReplaceOrder,
                self::DeleteOrder,
                self::CreateUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        } else {
            return [
                self::CreateOwnOrder,
                self::UpdateOwnOrder,
                self::DeleteOwnOrder
            ];
        }
    }
}
