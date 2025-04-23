<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderCheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

final class CheckoutOrderController extends Controller
{
    protected CartService $cartService;

    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function checkout(OrderCheckoutRequest $request): JsonResponse
    {

        $request->validated();
        $result = $this->orderService->checkout($request->address, $request->phone);
        if (!$result) {
            return response()->json(['error' => $result->message]);
        }

        return response()->json(['message' => $result->message]);

    }
}
