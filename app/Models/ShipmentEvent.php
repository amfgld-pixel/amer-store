<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class ShipmentEvent extends Model{protected $fillable=['shipment_id','status','external_status','description','payload','occurred_at'];protected function casts():array{return ['payload'=>'array','occurred_at'=>'datetime'];}public function shipment(){return $this->belongsTo(Shipment::class);}}
