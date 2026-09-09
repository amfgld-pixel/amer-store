<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class ShippingZone extends Model{protected $fillable=['name','country_code','city','base_rate','per_kg_rate','currency','is_active'];protected function casts():array{return ['base_rate'=>'decimal:2','per_kg_rate'=>'decimal:2','is_active'=>'boolean'];}}
