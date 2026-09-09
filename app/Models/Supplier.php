<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = ['code','name','name_bn','contact_person','email','phone','address','country_code','tax_number','currency','payment_terms','is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
}
