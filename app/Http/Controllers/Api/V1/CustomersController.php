<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\CustomerFilter;
use App\Models\User;
use App\Http\Resources\V1\UserResource;

class CustomersController extends ApiController
{
    /**
     * Get customers.
     *
     * Retrieves all users that created a order.
     *
     * @group Showing Customers
     */
    public function index(CustomerFilter $filters)
    {
        return UserResource::collection(
            User::has('orders')->filter($filters)->paginate()
        );
    }

    /**
     * Get an customer.
     *
     * Retrieves all users that created a order.
     *
     * @group Showing Customers
     */
    public function show(User $customer)
    {
        if ($this->include('orders')) {
            return new UserResource($customer->load('orders'));
        }

        return new UserResource($customer);
    }
}
