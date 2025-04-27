<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartRoutesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_get_cart(): void
    {
        $response = $this->get('api/cart');
        $response->assertStatus(200);
    }
    public function test_can_add_to_cart(): void
    {
        $product = Product::factory()->create();
        $response = $this->postJson('api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'Добавлено']);
    }
//    }
////    public function test_invalid_product_id(): void{
////        $response=$this->postJson('api/cart/add', [
////            'product_id'=>99999,
////            'quantity'=>1,
////        ]);
////        $response->assertJson('');
//    }
    public function test_cart_pizza_limit(): void
    {
        $product = Product::factory()->create(
            ['type'=>'pizza']
        );
        $response=$this->postJson('api/cart/add', [
            'product_id'=>$product->id,
            'quantity'=>10000,
        ]);
            $response->assertJson(['error'=>'Превышен лимит пицц']);
    }
    public function test_cart_drink_limit(): void
    {
        $product = Product::factory()->create(
            ['type'=>'drink']);
        $response=$this->postJson('api/cart/add', [
            'product_id'=>$product->id,
            'quantity'=>10000,
        ]);
        $response->assertJson(['error'=>'Превышен лимит напитков']);
    }
    public function test_remove_from_cart(): void{
        $product = Product::factory()->create();
        $response = $this->postJson('api/cart/add', [
            'product_id'=>$product->id,
            'quantity'=>1,
        ]);
        $response=$this->postJson('api/cart/remove',
            ['product_id'=>$product->id,]);
        $response->assertJson(['message'=>'Удалено']);

    }
    public function test_clear_cart(): void
    {
        $response = $this->postJson('api/cart/clear');
        $response->assertJson(['message'=>'Корзина очищена']);
    }

}
