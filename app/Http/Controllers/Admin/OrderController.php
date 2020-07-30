<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::active()->paginate(10);
//        dd($orders);
        return view('auth.orders.index',compact('orders'));
    }

    public function show(Order $order){
//        dd($order);
        return view('auth.orders.show', compact('order'));
    }
}
