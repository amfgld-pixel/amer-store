@extends('layouts.admin')
@section('title','Categories — AMER')
@section('heading','Categories')
@section('content')
<div class="catalog-split"><form class="panel form" method="post" action="{{ route('admin.categories.store') }}">@csrf<h2>New category</h2><div class="grid"><label><span>Name</span><input name="name" required></label><label><span>বাংলা নাম</span><input name="name_bn"></label><label><span>Slug</span><input name="slug" required></label><label><span>Order</span><input type="number" name="sort_order" value="0"></label><label class="wide"><span>Description</span><textarea name="description"></textarea></label><input type="hidden" name="is_active" value="1"></div><button>Create category</button></form>
<section class="panel"><h2>Category order</h2><div class="table">@foreach($categories as $category)<form class="row category-row" method="post" action="{{ route('admin.categories.update',$category) }}">@csrf @method('PUT')<input name="name" value="{{ $category->name }}"><input name="name_bn" value="{{ $category->name_bn }}"><input name="slug" value="{{ $category->slug }}"><input type="number" name="sort_order" value="{{ $category->sort_order }}"><input type="hidden" name="is_active" value="{{ (int)$category->is_active }}"><button>Save</button></form>@endforeach</div>{{ $categories->links() }}</section></div>
@endsection
