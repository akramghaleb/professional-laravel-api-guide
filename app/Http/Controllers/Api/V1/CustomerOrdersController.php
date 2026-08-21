<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\OrderFilter;
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
}
