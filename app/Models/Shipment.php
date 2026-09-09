<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class Shipment extends Model{protected $fillable=['uuid','order_id','courier','status','consignment_id','tracking_url','weight','shipping_cost','booking_response','booked_at','delivered_at'];protected function casts():array{return ['booking_response'=>'array','booked_at'=>'datetime','delivered_at'=>'datetime'];}public function order(){return $this->belongsTo(Order::class);}public function events(){return $this->hasMany(ShipmentEvent::class);}}
