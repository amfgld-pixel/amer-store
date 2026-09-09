<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class PosSyncBatch extends Model{protected $fillable=['batch_uuid','user_id','device_id','received_count','accepted_count','conflict_count','result'];protected function casts():array{return ['result'=>'array'];}public function conflicts(){return $this->hasMany(PosSyncConflict::class);}}
