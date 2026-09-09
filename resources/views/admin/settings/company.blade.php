@extends('layouts.admin')
@section('title','Company Settings — AMER')
@section('heading','Company settings')
@section('content')
<form class="panel form" method="post" action="{{ route('admin.settings.company.update',$company) }}">@csrf @method('PUT')
<div class="grid">@foreach(['name'=>'Company name','name_bn'=>'বাংলা নাম','email'=>'Email','phone'=>'Phone','whatsapp'=>'WhatsApp','facebook_url'=>'Facebook URL','country_code'=>'Country code','currency_code'=>'Currency','timezone'=>'Timezone'] as $field=>$label)<label><span>{{ $label }}</span><input name="{{ $field }}" value="{{ old($field,$company->$field) }}">@error($field)<em>{{ $message }}</em>@enderror</label>@endforeach
<label><span>Language</span><select name="locale"><option value="en" @selected($company->locale==='en')>English</option><option value="bn" @selected($company->locale==='bn')>বাংলা</option></select></label><label class="wide"><span>Address</span><textarea name="address">{{ old('address',$company->address) }}</textarea></label><label class="wide"><span>বাংলা ঠিকানা</span><textarea name="address_bn">{{ old('address_bn',$company->address_bn) }}</textarea></label><input type="hidden" name="is_active" value="1"></div><button>Save settings</button></form>
@endsection
