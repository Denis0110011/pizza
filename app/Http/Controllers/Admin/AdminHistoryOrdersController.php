<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;

final class AdminHistoryOrdersController extends Controller
{
    public function __invoke()
    {
        $orders = Order::orderBy('created_at', 'desc')->with('items.product')->get();

        return response()->json(['orders' => OrderResource::collection($orders)]);
    }
}
