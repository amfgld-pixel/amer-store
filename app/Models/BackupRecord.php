<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class BackupRecord extends Model{protected $fillable=['uuid','name','disk','path','size','checksum','status','created_by','completed_at'];protected function casts():array{return ['completed_at'=>'datetime'];}}
