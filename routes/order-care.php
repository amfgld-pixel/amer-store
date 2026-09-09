<?php
use App\Http\Controllers\OrderTrackingController;use App\Http\Controllers\ReturnController;use Illuminate\Support\Facades\Route;
Route::get('/track-order',[OrderTrackingController::class,'form'])->name('orders.track.form');Route::post('/track-order',[OrderTrackingController::class,'show'])->middleware('throttle:10,1')->name('orders.track');Route::middleware('auth')->group(function(){Route::post('/orders/{order}/returns',[ReturnController::class,'store'])->name('returns.store');Route::get('/returns/{uuid}',[ReturnController::class,'show'])->name('returns.show');});
