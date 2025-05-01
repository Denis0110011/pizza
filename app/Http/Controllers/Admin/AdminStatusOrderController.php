<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\AdminStatusOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AdminStatusOrderController extends Controller
{
    public function __invoke(AdminStatusOrderRequest $request, int $id): JsonResponse
    {
        $request->validated();
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Заказ не найден'], Response::HTTP_NOT_FOUND);
        }
        $order->status = $request->status;
        $order->save();

        return response()->json(['message' => 'Статус обновлен',
            'order' => new OrderResource($order)]);
    }
}
