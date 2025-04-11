<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

final class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $pizzas = Product::query()->where('type', 'pizza')->get();
        $drinks = Product::query()->where('type', 'drink')->get();

        return response()->json(['pizzas' => $pizzas, 'drinks' => $drinks]);
    }
}
