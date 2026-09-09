<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Company extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name','name_bn','email','phone','whatsapp','facebook_url','address','address_bn','country_code','currency_code','timezone','locale','logo_path','is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
}
