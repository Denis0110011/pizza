<?php

declare(strict_types=1);

namespace Tests\Feature\AdminRoutes;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

final class AdminProductsRoutesTest extends TestCase
{
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
