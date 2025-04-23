<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

final class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::all();

        return response()->json([
            'products' => ProductResource::collection($products),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::find($id);

        return response()->json(['product' => new ProductResource($product)]);
    }
}
