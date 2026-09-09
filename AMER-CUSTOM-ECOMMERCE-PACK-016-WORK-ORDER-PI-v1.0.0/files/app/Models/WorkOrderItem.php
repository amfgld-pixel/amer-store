<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WorkOrderItem extends Model
{
    protected $fillable=['work_order_id','order_item_id','line_order','product_name','variant_name','sku','quantity','uom','instructions'];
    protected function casts():array{return ['quantity'=>'decimal:2'];}
    public function workOrder(){return $this->belongsTo(WorkOrder::class);}
}

