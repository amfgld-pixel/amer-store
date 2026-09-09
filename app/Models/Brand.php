<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Brand extends Model
{
    use SoftDeletes;
    protected $fillable=['name','name_bn','slug','logo_path','description','sort_order','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function products(){ return $this->hasMany(Product::class); }
}
