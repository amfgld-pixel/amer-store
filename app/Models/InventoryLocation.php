<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryLocation extends Model
{
    use SoftDeletes;

    protected $fillable = ['code','name','name_bn','type','address','is_default','is_active'];
    protected function casts(): array { return ['is_default'=>'boolean','is_active'=>'boolean']; }
    public function inventoryItems() { return $this->hasMany(InventoryItem::class); }
}
