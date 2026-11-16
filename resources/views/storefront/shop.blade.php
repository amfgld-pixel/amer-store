@extends('layouts.storefront') @section('title',($currentCategory?->meta_title ?: $currentCategory?->name ?: 'Shop').' — AMER') @section('meta_description',$currentCategory?->meta_description ?: 'Shop AMER men, women, kids and accessories.')
@section('content')
<section class="shop-head">
    <p class="eyebrow">AMER COLLECTIONS</p>
    <h1>{{ $currentCategory?->name ?? 'Shop all' }}</h1>
    <p>{{ $currentCategory?->description ?: 'Contemporary pieces selected for modern life.' }}</p>
</section>

<div class="shop-layout">
    <aside class="filters category-tree">
        <h2>Collections</h2>
        <a class="{{ !$currentCategory ? 'active' : '' }}" href="{{ route('shop') }}">All products</a>

        @foreach($categories as $category)
            <div class="category-tree-group">
                <a class="category-main {{ ($currentCategory?->is($category) || $currentCategory?->parent_id === $category->id) ? 'active-parent' : '' }}"
                   href="{{ route('collection.show', $category) }}">{{ $category->name }}</a>

                @if($category->children->isNotEmpty())
                    <div class="category-children">
                        @foreach($category->children as $child)
                            <a class="{{ $currentCategory?->is($child) ? 'active' : '' }}"
                               href="{{ route('collection.show', $child) }}">{{ $child->name }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </aside>

    <section>
        <div class="shop-toolbar">
            <span>{{ $products->total() }} products</span>
            <form action="{{ url()->current() }}">
                <input name="q" value="{{ request('q') }}" placeholder="Search collection">
                <button>Search</button>
            </form>
        </div>
        <div class="product-grid">
            @forelse($products as $product)
                @include('storefront.partials.product-card', ['product' => $product])
            @empty
                <div class="empty">
                    <h2>No products found</h2>
                    <p>Add active products in AMER Admin or try another search.</p>
                </div>
            @endforelse
        </div>
        {{ $products->links() }}
    </section>
</div>

<style>
.category-tree-group{margin:0 0 8px}.category-tree .category-main{font-weight:600}.category-tree .active-parent{color:#111}.category-children{display:grid;gap:3px;margin:2px 0 8px 14px;padding-left:12px;border-left:1px solid #ded7cc}.category-children a{font-size:.92em;padding:5px 0}.category-children a::before{content:'—';margin-right:7px;color:#b49a6a}
</style>
@endsection
