<?php
namespace App\Services;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Dto\ResultDto;

class OrderService{
    protected CartService $cartService;
    public function __construct(CartService $cartService){
        $this->cartService = $cartService;
    }
    public function checkout(string $address, string $phone): ResultDto
    {
        $cart = $this->cartService->getCart();
        if ($cart->items()->count() === 0) {
            return ResultDto::fail('Корзина пуста');
        }

        $order = Order::create([
            'address' => $address,
            'phone' => $phone,
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
        return ResultDto::ok('Заказ создан' . $order->id);

    }
}
