@extends('layouts.admin')
@section('title','Work Orders — AMER') @section('heading','Automatic Work Orders')
@section('content')
<style>.doc-actions{display:flex;gap:10px;justify-content:flex-end;margin-bottom:18px}.doc-table{width:100%;border-collapse:collapse}.doc-table th,.doc-table td{padding:12px;border-bottom:1px solid #ddd;text-align:left}.doc-button{display:inline-block;background:#111;color:#fff;padding:10px 15px;text-decoration:none;border:0;border-radius:4px}</style>
<div class="doc-actions"><a class="doc-button" href="{{route('admin.work-orders.create')}}">+ New Work Order</a></div><section class="panel"><table class="doc-table"><thead><tr><th>Work Order</th><th>Online Order</th><th>Customer</th><th>Required</th><th>Priority</th><th>Status</th><th></th></tr></thead><tbody>@forelse($workOrders as $w)<tr><td><b>{{$w->number}}</b></td><td>{{$w->order->number}}</td><td>{{$w->order->customer_name}}</td><td>{{$w->required_date?->format('d M Y')??'—'}}</td><td>{{ucfirst($w->priority)}}</td><td>{{strtoupper($w->status)}}</td><td><a href="{{route('admin.work-orders.show',$w)}}">Open</a></td></tr>@empty<tr><td colspan="7">No Work Order created.</td></tr>@endforelse</tbody></table></section>{{$workOrders->links()}}
@endsection

