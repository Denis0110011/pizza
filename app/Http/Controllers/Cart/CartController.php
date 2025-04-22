<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        $cart = $this->cartService->getCart();
        $detailed = $cart->items->map(static fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]);

        return response()->json($detailed);

    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);
        $result = $this->cartService->addProduct($request->product_id, $request->quantity);
        if (!$result->success) {
            return response()->json(['error' => $result->message]);
        }

        return response()->json(['message' => $result->message]);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $result = $this->cartService->remove($request->product_id, $request->quantity);
        if (!$result) {
            return response()->json(['error' => $result->message]);
        }

        return response()->json(['message' => $result->message]);
    }

    public function clear(): JsonResponse
    {
        $result = $this->cartService->clear();

        return response()->json(['message' => $result->message]);
    }
}
