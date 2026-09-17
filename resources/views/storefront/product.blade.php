@extends('layouts.storefront')
@section('title', ($product->meta_title ?: $product->name).' — AMER')
@section('meta_description', $product->meta_description ?: $product->short_description ?: 'Shop '.$product->name.' at AMER.')
@section('content')
@php
    $mediaDiskName = config('filesystems.default', 'public');
    $mediaDiskName = $mediaDiskName === 'local' ? 'public' : $mediaDiskName;
    $mediaDisk = \Illuminate\Support\Facades\Storage::disk($mediaDiskName);
    $first = $product->variants->firstWhere('is_active', true) ?? $product->variants->first();
@endphp
<section class="product-page">
    <div class="gallery">
        @forelse($product->images->sortByDesc('is_primary') as $image)
            <img src="{{ $mediaDisk->url($image->path) }}" alt="{{ $image->alt_text ?: $product->name }}" width="900" height="1125">
        @empty
            <div class="product-placeholder large"><span>AMER</span><small>{{ $product->name }}</small></div>
        @endforelse
    </div>
    <div class="product-detail">
        <p class="eyebrow">{{ $product->brand?->name ?? 'AMER' }}</p><h1>{{ $product->name }}</h1>
        <div class="detail-price">{{ $first ? '৳'.number_format($first->price, 0) : 'Coming soon' }}</div>
        <p class="description">{{ $product->short_description ?: 'A considered AMER essential, designed for modern everyday life.' }}</p>
        @if($product->variants->where('is_active', true)->count())
            <form method="post" action="{{ route('cart.store', $first) }}">@csrf<label>Choose option<select name="variant_picker" onchange="this.form.action='{{ url('/cart') }}/'+this.value">@foreach($product->variants->where('is_active', true) as $variant)<option value="{{ $variant->id }}">{{ $variant->name ?: $variant->sku }} — ৳{{ number_format($variant->price, 0) }}</option>@endforeach</select></label><label>Quantity<input type="number" name="quantity" value="1" min="1" max="99"></label><button class="btn dark full">Add to bag</button></form>
        @endif
        <div class="commerce-actions">@auth<form method="post" action="{{ route('wishlist.toggle', $product) }}">@csrf<button>♡ Save to wishlist</button></form>@else<a href="{{ route('login') }}">♡ Login to save</a>@endauth<form method="post" action="{{ route('compare.toggle', $product) }}">@csrf<button>＋ Compare</button></form></div>
        <div class="accordions"><details open><summary>Product details</summary><p>{{ $product->description ?: 'Full product information will appear here when added from AMER Admin.' }}</p></details><details><summary>Delivery & returns</summary><p>Bangladesh and worldwide delivery options are selected at checkout.</p></details></div>
    </div>
</section>
@endsection
