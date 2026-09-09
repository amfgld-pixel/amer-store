<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProformaInvoiceItem extends Model
{
    protected $fillable=['proforma_invoice_id','order_item_id','line_order','product_name','variant_name','sku','quantity','unit_price','line_total'];
    protected function casts():array{return ['quantity'=>'decimal:2','unit_price'=>'decimal:2','line_total'=>'decimal:2'];}
    public function proformaInvoice(){return $this->belongsTo(ProformaInvoice::class);}
}

