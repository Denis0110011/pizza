<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

final class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::all();

        return view('products.index', ['products' => $products]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Product
    {
        return Product::find($id);
    }



}
