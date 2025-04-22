<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Str;

final class CartService
{
    public function getCart(): Cart
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
}
