<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\OrderFilter;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;

class CustomerOrdersController extends Controller
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
        $model = [
            'reference' => $request->input('data.attributes.reference'),
            'notes' => $request->input('data.attributes.notes'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $customer_id
        ];

        return new OrderResource(Order::create($model));
    }
}
