<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Пепперони',
            'description' => 'пепперони',
            'price' => 100.52,
            'type' => 'pizza',
        ]);
        Product::create([
            'name' => 'Маргарита',
            'description' => 'margarita',
            'price' => 100,
            'type' => 'pizza',
        ]);
        Product::create([
            'name' => 'cola',
            'description' => 'cola',
            'price' => 42,
            'type' => 'drink',
        ]);
    }
}
