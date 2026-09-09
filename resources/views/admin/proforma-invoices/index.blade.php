@extends('layouts.admin')
@section('title','Proforma Invoices — AMER') @section('heading','Proforma Invoices (PI)')
@section('content')
<style>.doc-actions{display:flex;justify-content:flex-end;margin-bottom:18px}.doc-table{width:100%;border-collapse:collapse}.doc-table th,.doc-table td{padding:12px;border-bottom:1px solid #ddd;text-align:left}.doc-button{background:#111;color:#fff;padding:10px 15px;text-decoration:none}</style>
<div class="doc-actions"><a class="doc-button" href="{{route('admin.proforma-invoices.create')}}">+ New Proforma Invoice</a></div><section class="panel"><table class="doc-table"><thead><tr><th>PI Number</th><th>Online Order</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>@forelse($invoices as $pi)<tr><td><b>{{$pi->number}}</b></td><td>{{$pi->order->number}}</td><td>{{$pi->customer_name}}</td><td>{{$pi->pi_date->format('d M Y')}}</td><td>{{$pi->currency}} {{number_format($pi->grand_total,2)}}</td><td>{{strtoupper($pi->status)}}</td><td><a href="{{route('admin.proforma-invoices.show',$pi)}}">Open</a></td></tr>@empty<tr><td colspan="7">No Proforma Invoice created.</td></tr>@endforelse</tbody></table></section>{{$invoices->links()}}
@endsection

