@extends('layouts.admin')
@section('title', ($product->exists ? 'Edit' : 'New').' Product — AMER')
@section('heading', $product->exists ? 'Edit product' : 'New product')

@section('content')
@php
    $mediaDiskName = config('filesystems.default', 'public');
    $mediaDiskName = $mediaDiskName === 'local' ? 'public' : $mediaDiskName;
    $mediaDisk = \Illuminate\Support\Facades\Storage::disk($mediaDiskName);
@endphp

<form class="panel form" method="post" enctype="multipart/form-data"
      action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if($product->exists) @method('PUT') @endif

    <div class="grid">
        <label><span>Name</span><input name="name" value="{{ old('name', $product->name) }}" required></label>
        <label><span>বাংলা নাম</span><input name="name_bn" value="{{ old('name_bn', $product->name_bn) }}"></label>
        <label><span>Slug</span><input name="slug" value="{{ old('slug', $product->slug) }}" required></label>
        <label><span>Brand</span><select name="brand_id"><option value="">No brand</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>@endforeach</select></label>
        <label><span>Type</span><select name="product_type"><option value="variable" @selected(old('product_type', $product->product_type ?: 'variable') === 'variable')>Variable</option><option value="simple" @selected(old('product_type', $product->product_type) === 'simple')>Simple</option></select></label>
        <label><span>Status</span><select name="status">@foreach(['draft','active','archived'] as $status)<option value="{{ $status }}" @selected(old('status', $product->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
        <label class="wide"><span>Categories</span><div class="checks">@foreach($categories as $category)<label><input type="checkbox" name="category_ids[]" value="{{ $category->id }}" @checked(in_array($category->id, old('category_ids', $product->categories->pluck('id')->all())))>{{ $category->name }}</label>@endforeach</div></label>
        <label class="wide"><span>Short description</span><textarea name="short_description">{{ old('short_description', $product->short_description) }}</textarea></label>
        <label class="wide"><span>Description</span><textarea name="description">{{ old('description', $product->description) }}</textarea></label>
    </div>

    <section class="product-media-editor">
        <div class="media-heading">
            <div><p class="eyebrow">PRODUCT MEDIA</p><h2>Product pictures</h2></div>
            <p>JPG, PNG or WebP · maximum 10 MB each · recommended 1200×1500</p>
        </div>
        <div class="media-upload-grid">
            <label class="upload-box">
                <strong>Main product image</strong>
                <span>Used as the product card and first gallery picture.</span>
                <input type="file" name="main_image" accept="image/jpeg,image/png,image/webp" data-main-image>
            </label>
            <label class="upload-box">
                <strong>Gallery images</strong>
                <span>Select up to 10 front, back and detail pictures.</span>
                <input type="file" name="gallery_images[]" accept="image/jpeg,image/png,image/webp" multiple data-gallery-images>
            </label>
        </div>
        <div class="new-image-preview" data-image-preview></div>

        @if($product->exists && $product->images->isNotEmpty())
            <h3>Saved pictures</h3>
            <div class="saved-image-grid">
                @foreach($product->images as $image)
                    <article class="saved-image">
                        <img src="{{ $mediaDisk->url($image->path) }}" alt="{{ $image->alt_text ?: $product->name }}">
                        <label><input type="radio" name="primary_image_id" value="{{ $image->id }}" @checked($image->is_primary)> Primary</label>
                        <label class="remove-choice"><input type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}"> Remove</label>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <h2>Variants</h2>
    <div id="variants">
        @php($rows = old('variants', $product->exists ? $product->variants->toArray() : [['name'=>'Default','sku'=>'','barcode'=>'','cost_price'=>0,'price'=>0,'compare_at_price'=>null,'weight'=>null,'is_active'=>1]]))
        @foreach($rows as $i => $variant)
            <div class="variant-row">
                <input name="variants[{{ $i }}][name]" value="{{ $variant['name'] ?? '' }}" placeholder="Variant">
                <input name="variants[{{ $i }}][sku]" value="{{ $variant['sku'] ?? '' }}" placeholder="SKU" required>
                <input name="variants[{{ $i }}][barcode]" value="{{ $variant['barcode'] ?? '' }}" placeholder="Barcode">
                <input type="number" step=".01" name="variants[{{ $i }}][cost_price]" value="{{ $variant['cost_price'] ?? 0 }}" placeholder="Cost">
                <input type="number" step=".01" name="variants[{{ $i }}][price]" value="{{ $variant['price'] ?? 0 }}" placeholder="Price" required>
                <input type="number" step=".01" name="variants[{{ $i }}][compare_at_price]" value="{{ $variant['compare_at_price'] ?? '' }}" placeholder="Compare price">
                <input type="number" step=".001" name="variants[{{ $i }}][weight]" value="{{ $variant['weight'] ?? '' }}" placeholder="Weight">
                <input type="hidden" name="variants[{{ $i }}][is_active]" value="1">
            </div>
        @endforeach
    </div>
    <input type="hidden" name="is_featured" value="0">
    <input type="hidden" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
    <button type="button" class="secondary" id="add-variant">Add variant</button>
    <button>Save product</button>
</form>

<style>
.product-media-editor{margin:32px 0;padding:24px;border:1px solid #e7dfd3;border-radius:18px;background:#fff}.media-heading{display:flex;justify-content:space-between;gap:20px;align-items:end;margin-bottom:18px}.media-heading h2{margin:2px 0}.media-heading p{color:#716b62}.media-upload-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.upload-box{display:flex!important;flex-direction:column;gap:8px;padding:20px;border:1px dashed #b89a61;border-radius:14px;background:#fcfaf6}.upload-box input{margin-top:8px}.new-image-preview,.saved-image-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:14px;margin-top:18px}.preview-tile,.saved-image{position:relative;border:1px solid #e7dfd3;border-radius:12px;padding:8px;overflow:hidden;background:white}.preview-tile img,.saved-image img{width:100%;aspect-ratio:4/5;object-fit:cover;border-radius:8px}.preview-tile small{display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;padding:6px 2px 0}.saved-image label{display:block;margin-top:7px}.remove-choice{color:#b42318}@media(max-width:760px){.media-upload-grid{grid-template-columns:1fr}.media-heading{display:block}}
</style>

<script>
document.getElementById('add-variant').onclick=function(){var box=document.getElementById('variants'),i=box.children.length,row=document.createElement('div');row.className='variant-row';row.innerHTML=['name','sku','barcode','cost_price','price','compare_at_price','weight'].map(function(n){return '<input name="variants['+i+']['+n+']" placeholder="'+n.replace('_',' ')+'" '+(n==='sku'||n==='price'?'required':'')+'>'}).join('')+'<input type="hidden" name="variants['+i+'][is_active]" value="1">';box.appendChild(row)};
(function(){
    const main=document.querySelector('[data-main-image]');
    const gallery=document.querySelector('[data-gallery-images]');
    const preview=document.querySelector('[data-image-preview]');
    function render(){
        preview.innerHTML='';
        const files=[];
        if(main.files[0]) files.push(main.files[0]);
        Array.from(gallery.files).slice(0,10).forEach(file=>files.push(file));
        files.forEach((file,index)=>{
            const tile=document.createElement('div'); tile.className='preview-tile';
            const img=document.createElement('img'); img.src=URL.createObjectURL(file); img.onload=()=>URL.revokeObjectURL(img.src);
            const text=document.createElement('small'); text.textContent=(index===0&&main.files[0]?'Main: ':'Gallery: ')+file.name;
            tile.append(img,text); preview.appendChild(tile);
        });
    }
    main.addEventListener('change',render); gallery.addEventListener('change',render);
})();
</script>
@endsection
