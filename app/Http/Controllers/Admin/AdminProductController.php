<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminProductController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|min:3|max:500',
            'price' => 'required|numeric|min:1',
            'type' => 'required|in:pizza,drink',
        ]);
        $product=Product::create($validated);
        return response()->json(['message'=>'Товар создан', 'product'=>$product->name]);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|min:3|max:100',
            'description' => 'nullable|string|min:3|max:500',
            'price' => 'sometimes|numeric|min:1',
            'type' => 'sometimes|in:pizza,drink',
        ]);
        $product=Product::findOrFail($id);
        $product->update($validated);
        return \response()->json(['message'=>'Товар обновлен', 'product'=>$product]);
    }
    public function destroy(int $id): JsonResponse
    {
        $product=Product::findOrFail($id);
        $product->delete();
        return response()->json(['message'=>'Товар удален', 'product'=>$product->name]);

    }
}
