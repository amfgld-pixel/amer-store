<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class PosSyncConflict extends Model{protected $fillable=['pos_sync_batch_id','client_uuid','type','message','payload','status'];protected function casts():array{return ['payload'=>'array'];}}
