<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\OrderFilter;
use App\Http\Requests\Api\V1\ReplaceOrderRequest;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Requests\Api\V1\UpdateOrderRequest;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Policies\V1\OrderPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerOrdersController extends ApiController
{
    protected $policyClass = OrderPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index($customer_id, OrderFilter $filters)
    {
        return OrderResource::collection(
            Order::where('user_id', $customer_id)->filter($filters)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request, $customer_id)
    {
        try {
            // policy
             $this->isAble('store', Order::class);

             return new OrderResource(Order::create($request->mappedAttributes([
                'customer' => 'user_id'
             ])));
         } catch (AuthorizationException $ex) {
             return $this->error('You are not authorized to create that resource', 403);
         }
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceOrderRequest $request, $customer_id,  $order_id)
    {
        // PUT
        try {
            $order = Order::where('id', $order_id)
                            ->where('user_id', $customer_id)
                            ->firstOrFail();

            $this->isAble('replace', $order);

            $order->update($request->mappedAttributes());
            return new OrderResource($order);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Order cannot be found.', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to update that resource', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $customer_id,  $order_id)
    {
        // PUT
        try {
            $order = Order::where('id', $order_id)
                            ->where('user_id', $customer_id)
                            ->firstOrFail();

            $this->isAble('update', $order);

            $order->update($request->mappedAttributes());
            return new OrderResource($order);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Order cannot be found.', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to update that resource', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($customer_id, $order_id)
    {
        try {
            $order = Order::where('id', $order_id)
                            ->where('user_id', $customer_id)
                            ->firstOrFail();

            $this->isAble('delete', $order);

            $order->delete();
            return $this->ok('Order successfully deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Order cannot be found.', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to delete that resource', 403);
        }
    }
}
