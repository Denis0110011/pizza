<?php

declare(strict_types=1);

namespace Tests\Feature\AdminRoutes;

use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

final class AdminOrdersRoutesTest extends TestCase
{
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
}
