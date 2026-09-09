<?php
use App\Http\Controllers\HealthController;use Illuminate\Support\Facades\Route;
Route::get('/health/ready',HealthController::class)->middleware('throttle:30,1')->name('health.ready');
