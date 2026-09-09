<?php

use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','role:super-admin,admin,inventory-manager'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('inventory',[InventoryController::class,'index'])->name('inventory.index');
    Route::post('inventory/adjust',[InventoryController::class,'adjust'])->name('inventory.adjust');
    Route::get('inventory/movements',[InventoryController::class,'movements'])->name('inventory.movements');
    Route::resource('suppliers',SupplierController::class)->only(['index','store','update']);
    Route::get('purchases',[PurchaseOrderController::class,'index'])->name('purchases.index');
    Route::get('purchases/create',[PurchaseOrderController::class,'create'])->name('purchases.create');
    Route::post('purchases',[PurchaseOrderController::class,'store'])->name('purchases.store');
    Route::get('purchases/{purchase}',[PurchaseOrderController::class,'show'])->name('purchases.show');
    Route::post('purchases/{purchase}/approve',[PurchaseOrderController::class,'approve'])->name('purchases.approve');
    Route::post('purchases/{purchase}/receive',[PurchaseOrderController::class,'receive'])->name('purchases.receive');
});
