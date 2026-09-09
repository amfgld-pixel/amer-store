<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class PosSale extends Model{protected $fillable=['uuid','number','user_id','inventory_location_id','status','payment_method','subtotal','discount_total','grand_total','paid_amount','change_amount','sold_at'];protected function casts():array{return ['sold_at'=>'datetime'];}public function items(){return $this->hasMany(PosSaleItem::class);}}
