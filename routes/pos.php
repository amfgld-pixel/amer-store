<?php
use App\Http\Controllers\Admin\PosController;use Illuminate\Support\Facades\Route;
Route::middleware(['auth','role:super-admin,admin,inventory-manager'])->prefix('admin/pos')->name('admin.pos.')->group(function(){Route::get('/',[PosController::class,'index'])->name('index');Route::get('/search',[PosController::class,'search'])->name('search');Route::post('/sales',[PosController::class,'store'])->name('store');Route::get('/receipt/{sale}',[PosController::class,'receipt'])->name('receipt');});
