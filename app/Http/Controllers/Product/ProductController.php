<?php

declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Dto\ResultDto;

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
        if (!$product) {
            return response()->json(['error'=>'Продукт не найден'],404);
        }

        return response()->json(['product' => new ProductResource($product)]);
    }
}
