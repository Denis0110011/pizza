<?php

declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::paginate(10);

        return response()->json([
            'products' => ProductResource::collection($products),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Продукт не найден'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['product' => new ProductResource($product)]);
    }
}
