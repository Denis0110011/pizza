<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

final class CartRoutesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCanGetCart(): void
    {
        $response = $this->get('api/cart');
        $response->assertStatus(200);
    }

    public function testCanAddToCart(): void
    {
        $product = Product::factory()->create();
        $response = $this->postJson('api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'Добавлено']);
    }

    //    }
    // //    public function test_invalid_product_id(): void{
    // //        $response=$this->postJson('api/cart/add', [
    // //            'product_id'=>99999,
    // //            'quantity'=>1,
    // //        ]);
    // //        $response->assertJson('');
    //    }
    public function testCartPizzaLimit(): void
    {
        $product = Product::factory()->create(
            ['type' => 'pizza'],
        );
        $response = $this->postJson('api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 10000,
        ]);
        $response->assertJson(['error' => 'Превышен лимит пицц']);
    }

    public function testCartDrinkLimit(): void
    {
        $product = Product::factory()->create(
            ['type' => 'drink'],
        );
        $response = $this->postJson('api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 10000,
        ]);
        $response->assertJson(['error' => 'Превышен лимит напитков']);
    }

    public function testRemoveFromCart(): void
    {
        $product = Product::factory()->create();
        $response = $this->postJson('api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $response = $this->postJson(
            'api/cart/remove',
            ['product_id' => $product->id],
        );
        $response->assertJson(['message' => 'Удалено']);

    }

    public function testClearCart(): void
    {
        $response = $this->postJson('api/cart/clear');
        $response->assertJson(['message' => 'Корзина очищена']);
    }
}
