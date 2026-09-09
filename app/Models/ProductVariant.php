<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProductVariant extends Model
{
    use SoftDeletes;
    protected $fillable=['product_id','name','sku','barcode','cost_price','price','compare_at_price','weight','weight_unit','track_inventory','is_active','sort_order'];
    protected function casts(): array { return ['cost_price'=>'decimal:2','price'=>'decimal:2','compare_at_price'=>'decimal:2','track_inventory'=>'boolean','is_active'=>'boolean']; }
    public function product(){ return $this->belongsTo(Product::class); }
    public function attributeValues(){ return $this->belongsToMany(AttributeValue::class); }
    public function images(){ return $this->hasMany(ProductImage::class); }
}
