<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    public $timestamps = true;
    protected $fillable = ['uuid','product_variant_id','inventory_location_id','type','quantity','unit_cost','balance_after','reference_type','reference_id','reason','created_by','occurred_at'];
    protected function casts(): array { return ['quantity'=>'decimal:4','unit_cost'=>'decimal:2','balance_after'=>'decimal:4','occurred_at'=>'datetime']; }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function location() { return $this->belongsTo(InventoryLocation::class, 'inventory_location_id'); }
    public function reference() { return $this->morphTo(); }
}
