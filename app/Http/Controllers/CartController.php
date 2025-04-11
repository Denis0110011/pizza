<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CartController extends Controller
{
    public function add(Request $request): JsonResponse
    {
        $request->validate(['productId' => 'required|exists:product,id', 'quantity' => 'required|integer|min:1']);
        $cart = session()->get('cart', []);
        $productId = $request->input('product_id');
        $productType = Product::find($productId)->type;
        if ($productType === 'pizza' && $this->getPizzaCount($cart) + $request->quatity > 10) {
            return response()->json(['error' => 'Maximum 10 pizzas allowed']);
        }
        if ($productType === 'drink' && $this->getDrinkCount($cart) + $request->quatity > 20) {
            return response()->json(['error' => 'Maximum 20 drinks allowed']);
        }
        if (isset($cart[$productId])) {
            $cart[$productId] += $request->quantity;
        } else {
            $cart[$productId] = $request->quantity;
        }
        session()->put('cart', $cart);

        return response()->json(['success' => 'Product added to cart']);
    }

    public function view(): JsonResponse
    {
        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();
        $items = [];
        $total = 0;

        foreach ($products as $product) {
            $quantity = $cart[$product->id];
            $total += $product->price * $quantity;
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function remove(int $productId): JsonResponse
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return response()->json(['success' => 'Product removed from cart']);
        }

        return response()->json(['error' => 'Product not found']);
    }

    private function getPizzaCount(array $cart): int
    {
        $pizzaId = Product::pizzas()->whereIn('id', array_keys($cart))->pluck('id');
        $count = 0;
        foreach ($pizzaId as $id) {
            $count += $cart[$id];
        }

        return $count;
    }

    private function getDrinkCount(array $cart): int
    {
        $drinkId = Product::drinks()->whereIn('id', array_keys($cart))->pluck('id');
        $count = 0;
        foreach ($drinkId as $id) {
            $count += $cart[$id];
        }

        return $count;
    }
}
