<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class CheckoutOrderController extends Controller
{
    private function getCart(): Cart
    {
        $token = request()->cookie('cart_token');
        if (!$token) {
            $token = Str::uuid()->toString();
            \Cookie::queue('cart_token', $token, 60 * 24 * 30);
        }
        $query = Cart::with('items.product')->firstOrCreate(['session_id' => $token]);
        if (auth()->check()) {
            $query->orwhere('user_id', auth()->user()->id);
        }
        $cart = $query->first();
        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $token,
                'user_id' => auth()->user()->id ?? null,
            ]);
        } elseif (auth()->check() && !$cart->user_id) {
            $cart->user_id = auth()->user()->id;
            $cart->save();
        }

        return $cart;
    }

    public function checkout(Request $request): JsonResponse
    {
        $cart = $this->getCart();
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
