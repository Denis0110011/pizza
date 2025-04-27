<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\ResultDto;
use App\Models\Cart;
use App\Models\Product;
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

    public function addProduct(int $productId, int $quantity = 1): ResultDto
    {
        $product = Product::find($productId);
        if (!$product) {
           return ResultDto::fail('Продукт не найден');
        }
        $cart = $this->getCart();
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
            $pizzas += $quantity;
        }
        if ($product->type === 'drink') {
            $drinks += $quantity;
        }
        if ($drinks > 20) {
            return ResultDto::fail('Превышен лимит напитков');
        }
        if ($pizzas > 10) {
            return ResultDto::fail('Превышен лимит пицц');
        }

        $item = $cart->items()->where('product_id', $product->id)->first();
        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return ResultDto::ok('Добавлено');
    }

    public function remove(int $productId, ?int $quantity): ResultDto
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();
        $item = $cart->items()->where('product_id', $product->id)->first();
        if (!$item) {
            return ResultDto::fail('Товар не найден');
        }
        if ($quantity) {
            $newQuantity = $item->quantity - $quantity;
            if ($newQuantity > 0) {
                $item->update(['quantity' => $newQuantity]);
            } else {
                $item->delete();
            }
        } else {
            $item->delete();
        }

        return ResultDto::ok('Удалено');
    }

    public function clear(): ResultDto
    {
        $cart = $this->getCart();
        $cart->items()->delete();

        return ResultDto::ok('Корзина очищена');
    }
}
