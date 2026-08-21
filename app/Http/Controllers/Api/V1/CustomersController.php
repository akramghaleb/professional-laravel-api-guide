<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;

class CustomersController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($this->include('orders')) {
            return UserResource::collection(User::with('orders')->paginate());
        }

        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        if ($this->include('orders')) {
            return new UserResource($customer->load('orders'));
        }

        return new UserResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
