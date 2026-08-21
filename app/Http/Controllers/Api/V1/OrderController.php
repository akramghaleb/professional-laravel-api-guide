<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\OrderFilter;
use App\Http\Requests\Api\V1\ReplaceOrderRequest;
use App\Models\Order;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Requests\Api\V1\UpdateOrderRequest;
use App\Http\Resources\V1\OrderResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends ApiController
{
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
        try {
            User::findOrFail($request->input('data.relationships.customer.data.id'));
        } catch (ModelNotFoundException $exception) {
            return $this->error('The provided customer id does not exist.', 404);
        }

        $model = [
            'reference' => $request->input('data.attributes.reference'),
            'notes' => $request->input('data.attributes.notes'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $request->input('data.relationships.customer.data.id')
        ];

        return new OrderResource(Order::create($model));
    }

    /**
     * Display the specified resource.
     */
    public function show($order_id)
    {
        try {
            $order = Order::findOrFail($order_id);

            if ($this->include('customer')) {
                return new OrderResource($order->load('customer'));
            }

            return new OrderResource($order);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Order cannot be found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $order_id)
    {
        // PATCH
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceOrderRequest $request, $order_id)
    {
        // PUT
        try {
            $order = Order::findOrFail($order_id);

            $model = [
                'reference' => $request->input('data.attributes.reference'),
                'notes' => $request->input('data.attributes.notes'),
                'status' => $request->input('data.attributes.status'),
                'user_id' => $request->input('data.relationships.customer.data.id')
            ];

            $order->update($model);

            return new OrderResource($order);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Order cannot be found.', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($order_id)
    {
        try {
            $order = Order::findOrFail($order_id);
            $order->delete();

            return $this->ok('Order successfully deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Order cannot be found.', 404);
        }
    }
}
