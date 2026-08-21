<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\OrderFilter;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerOrdersController extends ApiController
{
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
    public function store($customer_id, StoreOrderRequest $request)
    {
        try {
            User::findOrFail($customer_id);
        } catch (ModelNotFoundException $exception) {
            return $this->error('The provided customer id does not exist.', 404);
        }

        $model = [
            'reference' => $request->input('data.attributes.reference'),
            'notes' => $request->input('data.attributes.notes'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $customer_id
        ];

        return new OrderResource(Order::create($model));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($customer_id, $order_id)
    {
        try {
            $order = Order::findOrFail($order_id);

            if ($order->user_id == $customer_id) {
                $order->delete();
                return $this->ok('Order successfully deleted');
            }

            return $this->error('Order cannot be found.', 404);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Order cannot be found.', 404);
        }
    }
}
