<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class PosSaleItem extends Model{protected $fillable=['pos_sale_id','product_variant_id','sku','product_name','quantity','unit_price','line_total'];}
