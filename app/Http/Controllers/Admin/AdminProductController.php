<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\AdminProductStoreRequest;
use App\Http\Requests\Admin\Products\AdminProductUpdateRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

final class AdminProductController extends Controller
{
    public function store(AdminProductStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $product = Product::create($validated);

        return response()->json(['message' => 'Товар создан', 'product' => new ProductResource($product)]);
    }

    public function update(AdminProductUpdateRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $product = Product::findOrFail($id);
        $product->update($validated);

        return response()->json(['message' => 'Товар обновлен', 'product' => new ProductResource($product)]);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Товар удален', 'product' => new ProductResource($product)]);

    }
}
