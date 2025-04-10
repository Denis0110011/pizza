<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $pizzas=Product::query()->where('type','pizza')->get();
        $drinks=Product::query()->where('type','drink')->get();
        return response()->json(['pizzas'=>$pizzas,'drinks'=>$drinks]);
    }
}
