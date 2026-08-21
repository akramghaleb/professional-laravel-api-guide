<?php

namespace App\Policies\V1;

use App\Models\Order;
use App\Models\User;
use App\Permissions\V1\Abilities;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(User $user, Order $order)
    {
        if ($user->tokenCan(Abilities::DeleteOrder)) {
            return true;
        } else if ($user->tokenCan(Abilities::DeleteOwnOrder)) {
            return $user->id === $order->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can replace the order.
     */
    public function replace(User $user, Order $order)
    {
        if ($user->tokenCan(Abilities::ReplaceOrder)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create the order.
     */
    public function store(User $user)
    {
        return $user->tokenCan(Abilities::CreateOrder) ||
               $user->tokenCan(Abilities::CreateOwnOrder);
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(User $user, Order $order)
    {
        if ($user->tokenCan(Abilities::UpdateOrder)) {
            return true;
        } else if ($user->tokenCan(Abilities::UpdateOwnOrder)) {
            return $user->id === $order->user_id;
        }

        return false;
    }
}
