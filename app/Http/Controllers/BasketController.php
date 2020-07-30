<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function basket(){
        $orderId = session('orderId');
        if(!is_null($orderId)){
            $order = Order::findOrFail($orderId);
        }

        return view('basket', compact('order'));
    }



    public function basketPlace(){
        $orderId = session('orderId');
        if(is_null($orderId)){
            return redirect()->route('index');
        }
        $order = Order::find($orderId);
        return view('order',compact('order'));
    }



    public function basketAdd($productId){
        $orderId = session('orderId');
        if(is_null($orderId)){
           $order = Order::create();
           session(['orderId'=>$order->id]);
        } else {
            $order = Order::find($orderId);
        }

        if($order->products->contains($productId)){
            $privotRow = $order->products()->where('product_id',$productId)->first()->pivot;
            $privotRow->count++;
            $privotRow->update();
        }
        else $order->products()->attach($productId);

        if(Auth::check()){
            $order->user_id = Auth::id();
            $order->save();
        }

        $product = Product::find($productId);

        Order::changeFullSum($product->price);

        session()->flash('success','Ваш товар добавлен в корзину'.$product->name);

            return redirect()->route('basket');
    }

    public function baskedRemove($productId){
        $orderId = session('orderId');
        if(is_null($orderId)){
            return redirect()->route('basket');
        }
        $order = Order::find($orderId );
        if($order->products->contains($productId)){
            $pivotRow = $order->products()->where('product_id',$productId)->first()->pivot;
            if($pivotRow->count< 2){
                $order->products()->detach($productId);
            }else{
                $pivotRow->count--;
                $pivotRow->update();
            }
        }
        else $order->products()->attach($productId);
        $product = Product::find($productId);

        Order::changeFullSum(-$product->price);
        session()->flash('warning','Товар удален'.$product->name);

        return redirect()->route('basket');
    }

    public function basketConfirm(Request $request){

        $orderId = session('orderId');

        if(is_null($orderId)){
            return redirect()->route('index');
        }

        $order = Order::find($orderId);
        $success = $order->saveOrder($request->name, $request->phone);

        if($success){
            session()->flash('success','Ваш заказ принят в обработку!');
        } else {
            session()->flash('warning', 'Товар не добавлен');
        }

        Order::eraseOrderSum();

        return redirect()->route('index');
    }
}
