<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

final class ProductRoutesTest extends TestCase
{
    public function testListAllProduct(): void
    {
        $product = Product::factory()->create();
        $response = $this->getJson('api/products');
        $response->assertStatus(200);
    }

    public function testInvalidIdProduct(): void
    {
        $response = $this->getJson('api/products/666');
        $response->assertStatus(404);
        $response->assertJson(['error' => 'Продукт не найден']);
    }

    public function testValidIdProduct(): void
    {
        $product = Product::factory()->create();
        $response = $this->getJson("api/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertJson(['product' => [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
        ]]);
    }
}
