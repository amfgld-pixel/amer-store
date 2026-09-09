<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Attribute extends Model
{
    protected $fillable=['name','name_bn','slug','display_type','sort_order'];
    public function values(){ return $this->hasMany(AttributeValue::class)->orderBy('sort_order'); }
}
