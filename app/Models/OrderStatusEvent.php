<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class OrderStatusEvent extends Model{protected $fillable=['order_id','status','message','created_by','occurred_at'];protected function casts():array{return ['occurred_at'=>'datetime'];}}
