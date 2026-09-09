@extends('layouts.storefront')
@section('title', 'My Account — AMER')
@section('content')
<section class="account-shell">
    <div class="account-head"><div><p class="eyebrow">WELCOME BACK</p><h1>{{ auth()->user()->name }}</h1></div><div class="account-actions"><a href="{{ route('profile.edit') }}">Profile settings</a><a href="{{ route('orders.track.form') }}">Track an order</a><form method="POST" action="{{ route('logout') }}">@csrf<button>Sign out</button></form></div></div>
    <div class="account-panel"><h2>Purchase history</h2>
        @forelse($orders as $order)
            <a class="order-row" href="{{ route('customer.orders.show', $order) }}"><div><strong>{{ $order->number }}</strong><small>{{ $order->created_at->format('d M Y') }} · {{ $order->items_count }} item(s)</small></div><div><span class="status-pill">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span><strong>৳{{ number_format($order->grand_total, 0) }}</strong></div></a>
        @empty
            <div class="account-empty"><p>You have not placed an order yet.</p><a class="btn dark" href="{{ route('shop') }}">Start shopping</a></div>
        @endforelse
        {{ $orders->links() }}
    </div>
</section>
@endsection

