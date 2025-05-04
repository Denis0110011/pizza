<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class OrderRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckoutAuthentication(): void
    {
        $response = $this->postJson('api/order/checkout');
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function testCheckoutEmpty(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('api/order/checkout', [
            'address' => 'moskow',
            'phone' => '0123456789',
        ]);
        $response->assertJson(['error' => 'Корзина пуста']);
    }

    public function testCheckout(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->postJson('api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $response = $this->actingAs($user)->postJson(
            'api/order/checkout',
            ['address' => 'moskow', 'phone' => '0123456789'],
        );
        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
    }

    public function testOrdersHistory(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->has(OrderItem::factory()->count(3), 'items')
        ->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->getJson('api/order/history');
        $response->assertStatus(200);
        $response->assertJsonStructure(['orders']);

    }
}
