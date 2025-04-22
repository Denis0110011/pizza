<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
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
        $product = Product::findOrFail($request->product_id);
        $cart = $this->cartService->getCart();
        $pizzas = 0;
        $drinks = 0;
        foreach ($cart->items as $item) {
            if ($item->product->type === 'pizza') {
                $pizzas += $item->quantity;
            }
            if ($item->product->type === 'drink') {
                $drinks += $item->quantity;
            }
        }
        if ($product->type === 'pizza') {
            $pizzas += $request->quantity;
        }
        if ($product->type === 'drink') {
            $drinks += $request->quantity;
        }
        if ($drinks > 20) {
            return response()->json(['message' => 'Превышен лимит Напитков']);
        }
        if ($pizzas > 10) {
            return response()->json(['message' => 'Превышен лимит Пицц']);
        }

        $item = $cart->items()->where('product_id', $product->id)->first();
        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Добавлено']);
    }

    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $product = Product::findOrFail($request->product_id);
        $cart = $this->cartService->getCart();
        $item = $cart->items()->where('product_id', $product->id)->first();
        if (!$item) {
            return response()->json(['message' => 'Товар не найден в корзине']);
        }
        if ($request->filled('quantity')) {
            $newQuantity = $item->quantity - $request->quantity;
            if ($newQuantity > 0) {
                $item->update(['quantity' => $newQuantity]);
            } else {
                $item->delete();
            }
        } else {
            $item->delete();
        }

        return response()->json(['message' => 'Удалено']);
    }

    public function clear(): JsonResponse
    {
        $cart = $this->cartService->getCart();
        $cart->items()->delete();

        return response()->json(['message' => 'Корзина очищена']);
    }
}
