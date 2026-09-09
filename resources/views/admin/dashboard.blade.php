@extends('layouts.admin')
@section('title','Dashboard — AMER')
@section('heading','Good morning.')
@section('content')
<div class="metrics"><article><span>Company</span><strong>{{ $company?->name ?? 'Not configured' }}</strong></article><article><span>Users</span><strong>{{ $users }}</strong></article><article><span>Roles</span><strong>{{ $roles }}</strong></article><article><span>System</span><strong>Pack 001</strong></article></div>
<section class="panel"><div class="panel-title"><div><small>SECURITY</small><h2>Recent activity</h2></div></div><div class="table">@forelse($activity as $item)<div class="row"><span>{{ $item->event }}</span><span>{{ class_basename($item->auditable_type) }}</span><time>{{ $item->created_at?->diffForHumans() }}</time></div>@empty<div class="empty">No activity recorded yet.</div>@endforelse</div></section>
@endsection
