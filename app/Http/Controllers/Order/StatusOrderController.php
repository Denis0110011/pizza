<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class StatusOrderController extends Controller
{
    public function __invoke(Request $request, Order $order):JsonResponse{
        $request->validate(['status'=>'required|string|in:pending,processing,delivered,cancelled',
            'id'=>'integer|required']);
        $order=Order::find($request->id);
        if(!$order){
            return response()->json(['error'=>'Order not found'],404);
        }
        $order->status = $request->status;
        $order->save();
        return response()->json(['message'=>'Статус обновлен','status'=>$order->status]);
    }
}
