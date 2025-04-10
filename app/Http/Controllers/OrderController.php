<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request){
        if(Auth::check()){
            return response()->json(['error'=>'Unauthorized']);
        }
        $request->validate([
            'phone'=>'required|string|numeric|max:20',
            'email'=>'required|string|email|max:255',
            'address'=>'required|string|max:255'
        ]);
        $cart = Session::get('cart');
        if (count($cart)==0) {
            return response()->json(['error'=>'Cart is empty']);
        };
        $products = Product::whereIn('id', array_keys($cart))->get();
        $total = 0;
        foreach ($products as $product) {
            $total += $product->price * $cart[$product->id];
        };
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email,
            'price' => $total,
        ]);
        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $cart[$product->id],
                'price' => $product->price,
            ]);
        }
        Session::forget('cart');
        return response()->json(['message'=>'order created', 'order_id'=>$order->id]);
    }
    public function userOrders(){
        if(!Auth::check()){
            return response()->json(['error'=>'Unauthorized']);
        };
        $orders=Order::with('orderItems.product')->where('user_id',Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json(['orders'=>$orders]);
    }
    public function show(int $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        if(Auth::id()!==$order->user_id && !Auth::user()->isAdmin()){
            return response()->json(['error'=>'Unauthorized']);
        }
        return response()->json(['order'=>$order]);
    }
}
