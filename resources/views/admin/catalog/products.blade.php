@extends('layouts.admin')
@section('title','Products — AMER')
@section('heading','Products')
@section('content')
<div class="panel-title"><a class="admin-button" href="{{ route('admin.products.create') }}">Add product</a></div><section class="panel"><div class="table"><div class="row product-head"><b>Product</b><b>Brand</b><b>Variants</b><b>Status</b><b></b></div>@forelse($products as $product)<div class="row product-row"><span>{{ $product->name }}<small>{{ $product->name_bn }}</small></span><span>{{ $product->brand?->name ?? '—' }}</span><span>{{ $product->variants->count() }}</span><span>{{ ucfirst($product->status) }}</span><a href="{{ route('admin.products.edit',$product) }}">Edit</a></div>@empty<div class="empty">No products yet.</div>@endforelse</div>{{ $products->links() }}</section>
@endsection
