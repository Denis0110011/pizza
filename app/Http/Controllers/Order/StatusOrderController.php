<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class StatusOrderController extends Controller
{
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|string|in:pending,processing,delivered,cancelled']);
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Заказ не найден'], 404);
        }
        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Статус обновлен', 'order' => ['id' => $order->id,
            'status' => $order->status,
            'total' => $order->total,
            'items' => $order->items]]);
    }
}
