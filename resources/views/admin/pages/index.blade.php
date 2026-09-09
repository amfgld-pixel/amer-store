@extends('layouts.admin')
@section('heading', 'Custom Pages')
@section('content')
<p><a class="primary button-link" href="{{ route('admin.pages.create') }}">Create page</a></p><div class="panel">@foreach($pages as $page)<div class="page-admin-row"><div><strong>{{ $page->title }}</strong><small>/pages/{{ $page->slug }} · {{ $page->is_published ? 'Published' : 'Draft' }}</small></div><div><a href="{{ route('admin.pages.edit',$page) }}">Edit</a>@if($page->is_published)<a href="{{ route('pages.show',$page) }}" target="_blank">View</a>@endif<form method="POST" action="{{ route('admin.pages.destroy',$page) }}">@csrf @method('DELETE')<button class="danger">Delete</button></form></div></div>@endforeach{{ $pages->links() }}</div>
@endsection

