<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class MarketingEvent extends Model{protected $fillable=['event_uuid','name','session_id','user_id','properties','occurred_at'];protected function casts():array{return ['properties'=>'array','occurred_at'=>'datetime'];}}
