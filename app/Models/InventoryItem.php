<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = ['product_variant_id','inventory_location_id','quantity_on_hand','quantity_reserved','average_cost','reorder_level'];
    protected function casts(): array { return ['quantity_on_hand'=>'decimal:4','quantity_reserved'=>'decimal:4','average_cost'=>'decimal:2','reorder_level'=>'decimal:4']; }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
    public function location() { return $this->belongsTo(InventoryLocation::class, 'inventory_location_id'); }
    public function getAvailableQuantityAttribute(): string { return bcsub((string) $this->quantity_on_hand, (string) $this->quantity_reserved, 4); }
}
