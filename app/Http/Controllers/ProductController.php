<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $products = Product::all();

        return view('products.index', ['products' => $products]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):Product
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|min:3|max:500',
            'price' => 'required|numeric|min:1',
            'type' => 'required|in:pizza,drink',
        ]);

        return Product::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id):Product
    {
        return Product::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): void {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product):Product
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|min:3|max:100',
            'description' => 'nullable|string|min:3|max:500',
            'price' => 'sometimes|numeric|min:1',
            'type' => 'sometimes|in:pizza,drink',
        ]);
        $product->update($validated);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product):Response
    {
        $product->delete();

        return response()->noContent();
    }
}
