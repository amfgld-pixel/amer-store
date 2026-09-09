<?php
namespace App\Http\Controllers;use Illuminate\Support\Facades\DB;use Illuminate\Support\Facades\Cache;
class HealthController extends Controller{public function __invoke(){try{DB::select('select 1');Cache::put('healthcheck',true,10);return response()->json(['status'=>'ok','database'=>'ok','cache'=>'ok','time'=>now()->toIso8601String()]);}catch(\Throwable $e){return response()->json(['status'=>'degraded'],503);}}}
