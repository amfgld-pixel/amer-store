<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class ReturnRequestItem extends Model{protected $fillable=['return_request_id','order_item_id','quantity','condition'];public function orderItem(){return $this->belongsTo(OrderItem::class);}}
