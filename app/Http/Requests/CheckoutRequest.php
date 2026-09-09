<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CheckoutRequest extends FormRequest {public function authorize():bool{return true;}public function rules():array{return ['customer_name'=>['required','string','max:150'],'email'=>['nullable','email','max:150'],'phone'=>['required','string','max:30'],'country_code'=>['required','string','size:2'],'division'=>['nullable','string','max:100'],'city'=>['required','string','max:100'],'postal_code'=>['nullable','string','max:20'],'address'=>['required','string','max:1000'],'notes'=>['nullable','string','max:2000'],'shipping_method'=>['required','in:standard,express,international']];}}
