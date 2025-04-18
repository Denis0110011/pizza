<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Str;

class CartController extends Controller
{

private function getCart():Cart
{
    $token=request()->cookie('cart_token');
    if(!$token){
        $token=Str::uuid()->toString();
        \Cookie::queue('cart_token', $token, 60*24*30);
    }
    $query=Cart::with('items.product')->firstOrCreate(['session_id'=>$token]);
    if(auth()->check()){
        $query->orwhere('user_id',auth()->user()->id);
    }
    $cart=$query->first();
    if(!$cart){
        $cart=Cart::create([
            'session_id'=>$token,
            'user_id'=>auth()->user()->id ?? null,
        ]);
    }elseif (auth()->check() && !$cart->user_id){
        $cart->user_id=auth()->user()->id;
        $cart->save();
    }
    return $cart;
}
public function index():JsonResponse{
    $cart=$this->getCart();
    $detailed=$cart->items->map(function($item){
        return ['product_id'=>$item->product_id,'quantity'=>$item->quantity];
    });
    return response()->json($detailed);

}
public function add(Request $request):JsonResponse
{
    $request->validate([
        'product_id'=>'required|exists:products,id',
        'quantity'=>'integer|min:1'
    ]);
    $product=Product::findOrFail($request->product_id);
    $cart=$this->getCart();

    $pizzas=0;
    $drinks=0;
    foreach ($cart->items as $item){
        if($item->product->type==='pizza'){
            $pizzas+=$item->quantity;
        }
        if($item->product->type==='drink'){
            $drinks+=$item->quantity;
        }
    }
    if ($product->type==='pizza'){
        $pizzas+=$request->quantity;
    }
    if ($product->type==='drink'){
        $drinks+=$request->quantity;
    }
    if($drinks>20){
        return response()->json(['message'=>'Превышен лимит Напитков']);
    }
    if($pizzas>10){
        return response()->json(['message'=>'Превышен лимит Пицц']);
    }

    $item=$cart->items()->where('product_id',$product->id)->first();
    if($item){
        $item->quantity+=$request->quantity;
        $item->save();
    }else{
        $cart->items()->create([
            'product_id'=>$product->id,
            'quantity'=>$request->quantity
        ]);
    }
    return response()->json(['message'=>'Добавлено']);
}
public function remove(Request $request):JsonResponse{
    $request->validate([
        'product_id'=>'required|exists:products,id',
        'quantity'=>'nullable|integer|min:1'
    ]);
    $product=Product::findOrFail($request->product_id);
    $cart=$this->getCart();
    $item=$cart->items()->where('product_id',$product->id)->first();
    if(!$item){
        return \response()->json(['message'=>'Товар не найден в корзине']);
    }
    if($request->filled('quantity')){
        $newQuantity=$item->quantity-$request->quantity;
        if($newQuantity>0){
            $item->update(['quantity'=>$newQuantity]);
        }else{
            $item->delete();
        }
    }else{
        $item->delete();
    }
    return \response()->json(['message'=>'Удалено']);
}
public function clear():JsonResponse{
    $cart=$this->getCart();
    $cart->items()->delete();
    return response()->json(['message'=>'Корзина очищена']);
}


}
