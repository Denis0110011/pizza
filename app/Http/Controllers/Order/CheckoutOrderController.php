<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Dto\ResultDto;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\OrderService;

final class CheckoutOrderController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;



    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService =$orderService;
    }

    public function checkout(Request $request): JsonResponse
    {

        $request->validate([
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
        ]);
        $result = $this->orderService->checkout($request->address, $request->phone);
        if (!$result) {
            return response()->json(['error'=>$result->message]);
        }
        return response()->json(['message'=>$result->message]);

    }
}
