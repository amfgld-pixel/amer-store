@extends('layouts.admin')
@section('title','Categories — AMER')
@section('heading','Categories & Subcategories')

@section('content')
@if($errors->any())
    <div class="panel category-errors">
        <strong>Please correct the following:</strong>
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="catalog-split category-manager">
    <form class="panel form" method="post" action="{{ route('admin.categories.store') }}">
        @csrf
        <h2>New category or subcategory</h2>
        <p class="category-help">Choose a parent to create a subcategory. Leave it as “Main category” for Men, Women, Kids or Accessories.</p>

        <div class="grid">
            <label class="wide">
                <span>Parent category</span>
                <select name="parent_id">
                    <option value="">— Main category —</option>
                    @foreach($parentOptions as $parent)
                        <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </label>
            <label><span>Name</span><input name="name" value="{{ old('name') }}" required></label>
            <label><span>বাংলা নাম</span><input name="name_bn" value="{{ old('name_bn') }}"></label>
            <label><span>Slug</span><input name="slug" value="{{ old('slug') }}" placeholder="men-t-shirts" required></label>
            <label><span>Order</span><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" required></label>
            <label class="wide"><span>Description</span><textarea name="description">{{ old('description') }}</textarea></label>
            <input type="hidden" name="is_active" value="1">
        </div>
        <button>Create category</button>
    </form>

    <section class="panel">
        <div class="category-list-heading">
            <div><h2>Category hierarchy</h2><p>Main categories and their subcategories.</p></div>
        </div>

        <div class="category-tree">
            @forelse($categories as $category)
                @include('admin.catalog.partials.category-row', ['category' => $category, 'isChild' => false])

                @foreach($category->children as $child)
                    @include('admin.catalog.partials.category-row', ['category' => $child, 'isChild' => true])
                @endforeach
            @empty
                <p>No categories found.</p>
            @endforelse
        </div>

        {{ $categories->links() }}
    </section>
</div>

<style>
.category-help,.category-list-heading p{color:#746d63;margin-top:-5px}.category-errors{margin-bottom:20px;border-color:#d92d20;color:#8a1c13}.category-errors ul{margin-bottom:0}.category-tree{display:grid;gap:10px}.category-admin-row{display:grid;grid-template-columns:minmax(150px,1.2fr) minmax(130px,1fr) minmax(130px,1fr) minmax(150px,1fr) 85px 110px auto;gap:10px;align-items:center;padding:12px;border:1px solid #e7dfd3;border-radius:12px;background:#fff}.category-admin-row.is-child{margin-left:28px;background:#fcfaf6;border-left:4px solid #b89a61}.category-kind{font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#8b6b35}.category-admin-row input,.category-admin-row select{min-width:0;width:100%}.category-admin-row button{white-space:nowrap}.category-list-heading{display:flex;justify-content:space-between;align-items:end;margin-bottom:15px}.category-list-heading h2{margin-bottom:5px}@media(max-width:1050px){.category-admin-row{grid-template-columns:1fr 1fr}.category-admin-row button{width:100%}}@media(max-width:650px){.category-admin-row{grid-template-columns:1fr}.category-admin-row.is-child{margin-left:12px}}
</style>
@endsection
