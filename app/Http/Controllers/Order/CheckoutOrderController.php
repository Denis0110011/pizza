<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CheckoutOrderController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function checkout(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCart();
        if ($cart->items()->count() === 0) {
            return response()->json(['message' => 'Корзина пуста']);
        }
        $request->validate([
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
        ]);
        $order = Order::create([
            'address' => $request->address,
            'phone' => $request->phone,
            'user_id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'total' => $cart->items->sum(static fn($item) => $item->quantity * $item->product->price),
        ]);
        $order->save();
        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }
        $cart->items()->delete();

        return response()->json(['message' => 'Заказ создан', 'order' => $order->id]);
    }
}
