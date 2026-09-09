<?php
namespace App\Http\Controllers;use App\Models\Order;use Illuminate\Http\Request;
class OrderTrackingController extends Controller{public function form(){return view('storefront.track-order');}public function show(Request $r){$v=$r->validate(['number'=>'required|string','phone'=>'required|string']);$order=Order::with(['items'])->where('number',$v['number'])->where('phone',$v['phone'])->firstOrFail();$events=\App\Models\OrderStatusEvent::where('order_id',$order->id)->orderBy('occurred_at')->get();return view('storefront.order-tracking',compact('order','events'));}}
