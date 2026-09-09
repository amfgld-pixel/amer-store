<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class SecurityEvent extends Model{protected $fillable=['user_id','type','severity','ip_address','user_agent','context','occurred_at'];protected function casts():array{return ['context'=>'array','occurred_at'=>'datetime'];}}
