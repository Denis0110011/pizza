<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderRoutesTest extends TestCase
{
    use RefreshDatabase;
    public function test_checkout_authentication (): void
    {
        $response = $this->postJson('api/order/checkout');
        $response->assertStatus(401);
    }
    public function test_checkout_empty (): void{
        $user=User::factory()->create();
        $response = $this->actingAs($user)->postJson('api/order/checkout',[
            'address'=>'moskow',
            'phone'=>'0123456789',
        ]);
        $response->assertJson(['message'=>'Корзина пуста']);
    }
    public function test_checkout():void
    {
        $user=User::factory()->create();
        $product=Product::factory()->create();
        $response=$this->actingAs($user)->postJson('api/cart/add',[
            'product_id'=>$product->id,
            'quantity'=>1,
        ]);
        $response = $this->actingAs($user)->postJson('api/order/checkout',
            ['address'=>'moskow','phone'=>'0123456789']);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
    }
    public function test_orders_history():void{
        $user=User::factory()->create();
        $order=Order::factory()->has(OrderItem::factory()->count(3),'items')
        ->create(['user_id'=>$user->id]);
        $response=$this->actingAs($user)->getJson('api/order/history');
        $response->assertStatus(200);

    }
}
