<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\OrderFilter;
use App\Http\Requests\Api\V1\ReplaceOrderRequest;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Requests\Api\V1\UpdateOrderRequest;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Models\User;
use App\Policies\V1\OrderPolicy;

class CustomerOrdersController extends ApiController
{
    protected $policyClass = OrderPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(User $customer, OrderFilter $filters)
    {
        return OrderResource::collection(
            Order::where('user_id', $customer->id)->filter($filters)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request, User $customer)
    {
        if ($this->isAble('store', Order::class)) {
            return new OrderResource(Order::create($request->mappedAttributes([
                'customer' => 'user_id'
            ])));
        }

        return $this->notAuthorized('You are not authorized to create that resource');
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceOrderRequest $request, User $customer, Order $order)
    {
        // PUT
        if ($this->isAble('replace', $order)) {
            $order->update($request->mappedAttributes());
            return new OrderResource($order);
        }

        return $this->notAuthorized('You are not authorized to update that resource');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, User $customer, Order $order)
    {
        // PUT
        if ($this->isAble('update', $order)) {
            $order->update($request->mappedAttributes());
            return new OrderResource($order);
        }

        return $this->notAuthorized('You are not authorized to update that resource');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $customer, Order $order)
    {
        if ($this->isAble('delete', $order)) {
            $order->delete();
            return $this->ok('Order successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource');
    }
}
