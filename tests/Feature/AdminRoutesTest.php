<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminRoutesTest extends TestCase
{
    public function test_non_admin_user_cannot_access():void
    {
        $user=User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/admin/orders');
        $response->assertStatus(403);
        $response->assertJson(['error'=>'Доступ запрещен']);
    }
    public function test_admin_orders_history():void{
        $user=User::factory()->create(['is_admin'=>1]);
        $response = $this->actingAs($user)->getJson('api/admin/orders');
        $response->assertStatus(200);
        $response->assertJsonStructure(['orders']);
    }
    public function test_admin_can_change_order_status():void
    {
        $user=User::factory()->create(['is_admin'=>1]);
        $order=Order::factory()->create();
        $response=$this->actingAs($user)->patchJson('api/admin/orders/'.$order->id.'/status',
            ['status'=>'processing']);
        $response->assertStatus(200);
        $response->assertJson(['message'=>'Статус обновлен']);
    }
    public function test_admin_cannot_change_status_invalid_id_order():void
    {
        $user=User::factory()->create(['is_admin'=>1]);
        $response = $this->actingAs($user)->patchJson('api/admin/orders/11111111/status');
        $response->assertStatus(404);
        $response->assertJson(['error'=>'Заказ не найден']);

    }
    public function test_admin_can_create_product():void
    {
        $user=User::factory()->create(['is_admin'=>1]);
        $response = $this->actingAs($user)->postJson('api/admin/products/add',
            [
                'name' => 'new product',
                'description' => 'new product description',
                'price' => 100.50,
                'type' => 'pizza'
            ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['product']);
    }
    public function test_admin_can_delete_product():void
    {
        $user=User::factory()->create(['is_admin'=>1]);
        $product=Product::factory()->create();
        $response = $this->actingAs($user)->deleteJson("api/admin/products/{$product->id}/delete");
        $response->assertStatus(200);
        $response->assertJsonStructure(['message','product']);
    }
    public function test_admin_can_update_product():void{
        $user=User::factory()->create(['is_admin'=>1]);
        $product=Product::factory()->create();
        $response = $this->actingAs($user)->patchJson("api/admin/products/{$product->id}/update",[
            'name' => 'update product',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['message','product']);
    }
}
