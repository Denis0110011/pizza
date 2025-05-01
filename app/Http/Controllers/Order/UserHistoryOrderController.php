<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

final class UserHistoryOrderController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = auth()->user();
        $orders = Order::with('items.product')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return response()->json(
            ['orders' => OrderResource::collection($orders)],
        );
    }
}
