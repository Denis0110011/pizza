<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

final class HistoryOrderController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $orders = Order::with('items.product')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json(['orders' => $orders]);
    }
}
