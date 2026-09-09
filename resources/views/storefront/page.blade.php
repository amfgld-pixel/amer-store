@extends('layouts.storefront')
@section('title', $page->meta_title ?: $page->title.' — AMER')
@section('meta_description', $page->meta_description ?: '')
@section('content')
@if($page->hero_image_path)<div class="content-hero"><img src="{{ asset('storage/'.$page->hero_image_path) }}" alt="{{ $page->title }}"></div>@endif
<article class="content-page"><p class="eyebrow">AMER</p><h1>{{ $page->title }}</h1><div class="prose">{!! nl2br(e($page->content)) !!}</div></article>
@endsection

