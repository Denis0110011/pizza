<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

final class AdminRoutesTest extends TestCase
{
    public function testNonAdminUserCannotAccess(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/admin/orders');
        $response->assertStatus(403);
        $response->assertJson(['error' => 'Доступ запрещен']);
    }

    public function testAdminOrdersHistory(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $response = $this->actingAs($user)->getJson('api/admin/orders');
        $response->assertStatus(200);
        $response->assertJsonStructure(['orders']);
    }

    public function testAdminCanChangeOrderStatus(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $order = Order::factory()->create();
        $response = $this->actingAs($user)->patchJson(
            'api/admin/orders/' . $order->id . '/status',
            ['status' => 'processing'],
        );
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Статус обновлен']);
    }

    public function testAdminCannotChangeStatusInvalidIdOrder(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $response = $this->actingAs($user)->patchJson('api/admin/orders/11111111/status');
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Заказ не найден']);

    }

    public function testAdminCanCreateProduct(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $response = $this->actingAs($user)->postJson(
            'api/admin/products/add',
            [
                'name' => 'new product',
                'description' => 'new product description',
                'price' => 100.50,
                'type' => 'pizza',
            ],
        );
        $response->assertStatus(200);
        $response->assertJsonStructure(['product']);
    }

    public function testAdminCanDeleteProduct(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->deleteJson("api/admin/products/{$product->id}/delete");
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'product']);
    }

    public function testAdminCanUpdateProduct(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->patchJson("api/admin/products/{$product->id}/update", [
            'name' => 'update product',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'product']);
    }
}
