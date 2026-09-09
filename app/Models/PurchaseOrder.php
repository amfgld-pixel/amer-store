<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = ['uuid','number','supplier_id','inventory_location_id','order_date','expected_date','status','currency','exchange_rate','subtotal','discount_total','tax_total','shipping_total','grand_total','notes','created_by','approved_by','approved_at','received_at'];
    protected function casts(): array { return ['order_date'=>'date','expected_date'=>'date','approved_at'=>'datetime','received_at'=>'datetime','exchange_rate'=>'decimal:6','grand_total'=>'decimal:2']; }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function location() { return $this->belongsTo(InventoryLocation::class, 'inventory_location_id'); }
    public function items() { return $this->hasMany(PurchaseOrderItem::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
