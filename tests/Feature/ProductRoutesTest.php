<?php

namespace Tests\Feature;

use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductRoutesTest extends TestCase
{
    public function test_list_all_Product():void{
        $product=Product::factory()->create();
        $response=$this->getJson('api/products');
        $response->assertStatus(200);
    }
    public function test_invalid_id_product():void
    {
        $response=$this->getJson('api/products/666');
        $response->assertStatus(404);
        $response->assertJson( ['error'=>'Продукт не найден']);
    }
    public function test_valid_id_product():void{
        $product=Product::factory()->create();
        $response=$this->getJson("api/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertJson( ['product'=>[
            'id'=>$product->id,
            'name'=>$product->name,
            'description'=>$product->description,
            'price'=>$product->price,
        ]]);
    }
}
