<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class NotificationLog extends Model{protected $fillable=['order_id','channel','recipient','template','status','payload','failure_reason','sent_at'];protected function casts():array{return ['payload'=>'array','sent_at'=>'datetime'];}}
