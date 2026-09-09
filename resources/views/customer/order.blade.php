@extends('layouts.storefront')
@section('title', 'Order '.$order->number.' — AMER')
@section('content')
<section class="account-shell">
    <div class="account-head"><div><p class="eyebrow">ORDER DETAILS</p><h1>{{ $order->number }}</h1><p>Placed {{ $order->created_at->format('d M Y, h:i A') }}</p></div><a class="text-link" href="{{ route('customer.account') }}">← Purchase history</a></div>
    <div class="account-columns"><div class="account-panel"><h2>Products</h2>@foreach($order->items as $item)<div class="line-item"><div><strong>{{ $item->product_name }}</strong><small>{{ $item->variant_name }} · Qty {{ $item->quantity }}</small></div><strong>৳{{ number_format($item->line_total, 0) }}</strong></div>@endforeach<div class="order-total"><span>Total</span><strong>৳{{ number_format($order->grand_total, 0) }}</strong></div></div>
    <div class="account-panel"><h2>Tracking</h2><p><span class="status-pill">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span></p>@forelse($order->statusEvents as $event)<div class="timeline-item"><strong>{{ str_replace('_', ' ', ucfirst($event->status)) }}</strong><small>{{ optional($event->occurred_at)->format('d M Y, h:i A') }}</small><p>{{ $event->note }}</p></div>@empty<p>No tracking event has been posted yet.</p>@endforelse</div></div>
</section>
@endsection

