<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProformaInvoice extends Model
{
    protected $fillable=['number','order_id','status','pi_date','valid_until','customer_name','email','phone','address','currency','subtotal','discount_total','shipping_total','tax_total','grand_total','payment_terms','delivery_terms','bank_details','notes','created_by','approved_by','approved_at'];
    protected function casts():array{return ['pi_date'=>'date','valid_until'=>'date','approved_at'=>'datetime','subtotal'=>'decimal:2','grand_total'=>'decimal:2'];}
    public function order():BelongsTo{return $this->belongsTo(Order::class);}
    public function items():HasMany{return $this->hasMany(ProformaInvoiceItem::class)->orderBy('line_order');}
}

