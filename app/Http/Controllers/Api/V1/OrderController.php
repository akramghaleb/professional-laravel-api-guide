<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\OrderFilter;
use App\Http\Requests\Api\V1\ReplaceOrderRequest;
use App\Models\Order;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Requests\Api\V1\UpdateOrderRequest;
use App\Http\Resources\V1\OrderResource;
use App\Policies\V1\OrderPolicy;

class OrderController extends ApiController
{
    protected $policyClass = OrderPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(OrderFilter $filters)
    {
        return OrderResource::collection(Order::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        if ($this->isAble('store', Order::class)) {
            return new OrderResource(Order::create($request->mappedAttributes()));
        }

        return $this->notAuthorized('You are not authorized to create that resource');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        if ($this->include('customer')) {
            return new OrderResource($order->load('customer'));
        }

        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        // PATCH
        if ($this->isAble('update', $order)) {
            $order->update($request->mappedAttributes());
            return new OrderResource($order);
        }

        return $this->notAuthorized('You are not authorized to update that resource');
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceOrderRequest $request, Order $order)
    {
        // PUT
        if ($this->isAble('replace', $order)) {
            $order->update($request->mappedAttributes());
            return new OrderResource($order);
        }

        return $this->notAuthorized('You are not authorized to update that resource');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // policy
        if ($this->isAble('delete', $order)) {
            $order->delete();
            return $this->ok('Order successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource');
    }
}
