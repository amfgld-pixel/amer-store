<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class MarketingSetting extends Model{protected $fillable=['key','value','is_secret'];protected function casts():array{return ['is_secret'=>'boolean'];}public static function value(string $key,?string $default=null):?string{return static::where('key',$key)->value('value')??$default;}}
