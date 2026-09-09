<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = ['purchase_order_id','product_variant_id','ordered_quantity','received_quantity','unit_cost','discount','tax','line_total'];
    protected function casts(): array { return ['ordered_quantity'=>'decimal:4','received_quantity'=>'decimal:4','unit_cost'=>'decimal:2','line_total'=>'decimal:2']; }
    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'product_variant_id'); }
}
