<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrderItem extends Model {protected $fillable=['order_id','product_id','product_variant_id','product_name','variant_name','sku','quantity','unit_price','line_total','snapshot'];protected function casts():array{return ['quantity'=>'decimal:2','unit_price'=>'decimal:2','line_total'=>'decimal:2','snapshot'=>'array'];}public function order(){return $this->belongsTo(Order::class);}}
