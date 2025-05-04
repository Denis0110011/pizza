<?php

declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Services\ProductCacheService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProductController extends Controller
{
    protected ProductCacheService $productCacheService;

    public function __construct(ProductCacheService $productCacheService)
    {
        $this->productCacheService = $productCacheService;
    }

    public function index(ProductRequest $request): JsonResponse
    {
        $request->validated();
        $products = $this->productCacheService->getPaginatedProducts((int) ($request->perPage ?? 10), (int) ($request->page ?? 1));

        return response()->json([
            'products' => ProductResource::collection($products),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $product = $this->productCacheService->getProductById($id);

        if (!$product) {
            return response()->json(['error' => 'Продукт не найден'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['product' => new ProductResource($product)]);
    }
}
