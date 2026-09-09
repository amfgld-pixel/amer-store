<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerAccountController extends Controller
{
    public function index(Request $request): View
    {
        return view('customer.dashboard', [
            'orders' => $request->user()->orders()->withCount('items')->latest()->paginate(15),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless((int) $order->user_id === (int) $request->user()->id, 403);

        return view('customer.order', [
            'order' => $order->load(['items', 'statusEvents']),
        ]);
    }
}

