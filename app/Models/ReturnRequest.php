<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class ReturnRequest extends Model{protected $fillable=['uuid','number','order_id','user_id','type','status','reason','details','requested_amount','approved_amount','reviewed_by','reviewed_at'];protected function casts():array{return ['reviewed_at'=>'datetime'];}public function order(){return $this->belongsTo(Order::class);}public function items(){return $this->hasMany(ReturnRequestItem::class);}}
