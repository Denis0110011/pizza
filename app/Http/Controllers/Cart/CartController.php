<?php

declare(strict_types=1);

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\RemoveFromCartRequest;
use App\Http\Resources\Cart\CartItemResource;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): AnonymousResourceCollection
    {
        $cart = $this->cartService->getCart();

        return CartItemResource::collection($cart->items);
    }

    public function add(AddToCartRequest $request): JsonResponse
    {
        $request->validated();
        $result = $this->cartService->addProduct($request->product_id, $request->quantity);
        if (!$result->success) {
            return response()->json(['error' => $result->message, 'response' => $result->response]);
        }

        return response()->json(['message' => $result->message, 'response' => $result->response]);
    }

    public function remove(RemoveFromCartRequest $request): JsonResponse
    {
        $request->validated();
        $result = $this->cartService->remove($request->product_id, $request->quantity);
        if (!$result->success) {
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
