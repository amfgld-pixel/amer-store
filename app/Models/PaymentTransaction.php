<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class PaymentTransaction extends Model{protected $fillable=['uuid','order_id','gateway','status','gateway_transaction_id','merchant_reference','amount','currency','request_payload','response_payload','failure_reason','verified_at'];protected function casts():array{return ['amount'=>'decimal:2','request_payload'=>'array','response_payload'=>'array','verified_at'=>'datetime'];}public function order(){return $this->belongsTo(Order::class);}}
