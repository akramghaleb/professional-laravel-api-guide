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
     * Get all orders
     *
     * Retrieves all orders created by a specific user.
     *
     * @group Managing Orders by Customer
     *
     * @urlParam customer_id integer required The customer's ID. No-example
     *
     * @queryParam sort string Data field(s) to sort by. Separate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=name
     * @queryParam filter[name] Filter by name. Wildcards are supported.
     * @queryParam filter[email] Filter by email. Wildcards are supported.
     */
    public function index(User $customer, OrderFilter $filters)
    {
        return OrderResource::collection(
            Order::where('user_id', $customer->id)->filter($filters)->paginate()
        );
    }

    /**
     * Create a order
     *
     * Creates a order for the specific user.
     *
     * @group Managing Orders by Customer
     *
     * @urlParam customer_id integer required The customer's ID. No-example
     *
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
     * Replace an customer's order
     *
     * Replaces an customer's order.
     *
     * @group Managing Orders by Customer
     * @urlParam customer_id integer required The customer's ID. No-example
     * @urlParam order_id integer required The order ID. No-example
     * @response {"data":{"type":"order","id":107,"attributes":{"reference":"ORD-10432","notes":"Priority delivery","status":"paid","createdAt":"2024-03-26T04:40:48.000000Z","updatedAt":"2024-03-26T04:40:48.000000Z"},"relationships":{"customer":{"data":{"type":"user","id":1},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/customers\/1"}}},"links":{"self":"http:\/\/localhost:8000\/api\/v1\/orders\/107"}}}
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
     * Update an customer's order
     *
     * Updates an customer's order.
     *
     * @group Managing Orders by Customer
     * @urlParam customer_id integer required The customer's ID. No-example
     * @urlParam order_id integer required The order ID. No-example
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
     * Delete an customer's order
     *
     * Deletes an customer's order.
     *
     * @group Managing Orders by Customer
     * @urlParam customer_id integer required The customer's ID. No-example
     * @urlParam id integer required The order ID. No-example
     * @response {}
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
