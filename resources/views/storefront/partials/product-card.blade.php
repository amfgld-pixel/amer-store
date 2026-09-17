@php
    $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $variant = $product->variants->firstWhere('is_active', true) ?? $product->variants->first();
    $mediaDiskName = config('filesystems.default', 'public');
    $mediaDiskName = $mediaDiskName === 'local' ? 'public' : $mediaDiskName;
@endphp
<article class="product-card">
    <a class="product-media" href="{{ route('product.show', $product) }}">
        @if($image)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk($mediaDiskName)->url($image->path) }}" alt="{{ $image->alt_text ?: $product->name }}" loading="lazy" width="720" height="900">
        @else
            <div class="product-placeholder"><span>AMER</span><small>{{ $product->name }}</small></div>
        @endif
        @if($product->is_featured)<span class="tag">AMER EDIT</span>@endif
    </a>
    <div class="product-info"><div><a href="{{ route('product.show', $product) }}">{{ $product->name }}</a><small>{{ $product->brand?->name ?? 'AMER' }}</small></div><div class="price">@if($variant)৳{{ number_format($variant->price, 0) }}@else<span>Coming soon</span>@endif</div></div>
</article>
