<?php
use App\Http\Controllers\Admin\PosSyncController;use Illuminate\Support\Facades\Route;
Route::middleware(['auth','throttle:30,1'])->post('/admin/pos/sync',[PosSyncController::class,'store'])->name('admin.pos.sync');
