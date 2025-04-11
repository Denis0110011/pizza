<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

final class OrderController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            'admin',
        ];
    }

    public function index()
    {
        $orders = Order::with('user', 'items.product')->get();

        return response()->json($orders);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,processing,delivering,completed,canceled']);
        $order = $this->updateStatus($request->status, $id);

        return response()->json($order);
    }
}
