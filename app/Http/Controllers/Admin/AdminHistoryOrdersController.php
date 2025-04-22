<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class AdminHistoryOrdersController extends Controller
{
    public function __invoke(){
        $orders = Order::orderBy('created_at', 'desc')->with('items.product')->get();
        return response()->json(['orders' => $orders]);
    }
}
