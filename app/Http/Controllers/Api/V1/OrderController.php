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
     * Get all orders
     *
     * @group Managing Orders
     * @queryParam sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=reference,-createdAt
     * @queryParam filter[status] Filter by status: pending, paid, shipped, cancelled. No-example
     * @queryParam filter[reference] Filter by reference. Wildcards are supported. Example: *ORD-1*
     */
    public function index(OrderFilter $filters)
    {
        return $this->resource(
            'Orders retrieved successfully.',
            OrderResource::collection(Order::filter($filters)->paginate()),
        );
    }

    /**
     * Create a order
     *
     * Creates a new order record. Users can only create orders for themselves. Managers can create orders for any user.
     *
     * @group Managing Orders
     *
     * @response {"data":{"type":"order","id":107,"attributes":{"reference":"ORD-10432","notes":"Priority delivery","status":"paid","createdAt":"2024-03-26T04:40:48.000000Z","updatedAt":"2024-03-26T04:40:48.000000Z"},"relationships":{"customer":{"data":{"type":"user","id":1},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/customers\/1"}}},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/orders\/107"}}}
     */
    public function store(StoreOrderRequest $request)
    {
        if ($this->isAble('store', Order::class)) {
            return $this->resource(
                'Order created successfully.',
                new OrderResource(Order::create($request->mappedAttributes())),
                201,
            );
        }

        return $this->notAuthorized('You are not authorized to create that resource');
    }

    /**
     * Show a specific order.
     *
     * Display an individual order.
     *
     * @group Managing Orders
     *
     */
    public function show(Order $order)
    {
        if ($this->include('customer')) {
            return $this->resource('Order retrieved successfully.', new OrderResource($order->load('customer')));
        }

        return $this->resource('Order retrieved successfully.', new OrderResource($order));
    }

    /**
     * Update Order
     *
     * Update the specified order in storage.
     *
     * @group Managing Orders
     *
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        // PATCH
        if ($this->isAble('update', $order)) {
            $order->update($request->mappedAttributes());
            return $this->resource('Order updated successfully.', new OrderResource($order));
        }

        return $this->notAuthorized('You are not authorized to update that resource');
    }

    /**
     * Replace Order
     *
     * Replace the specified order in storage.
     *
     * @group Managing Orders
     *
     */
    public function replace(ReplaceOrderRequest $request, Order $order)
    {
        // PUT
        if ($this->isAble('replace', $order)) {
            $order->update($request->mappedAttributes());
            return $this->resource('Order replaced successfully.', new OrderResource($order));
        }

        return $this->notAuthorized('You are not authorized to update that resource');
    }

    /**
     * Delete order.
     *
     * Remove the specified resource from storage.
     *
     * @group Managing Orders
     *
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
