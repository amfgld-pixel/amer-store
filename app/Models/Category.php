<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use SoftDeletes;
    protected $fillable=['parent_id','name','name_bn','slug','description','description_bn','image_path','sort_order','meta_title','meta_description','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function parent(){ return $this->belongsTo(self::class,'parent_id'); }
    public function children(){ return $this->hasMany(self::class,'parent_id')->orderBy('sort_order'); }
    public function products(){ return $this->belongsToMany(Product::class); }
}
