<?php
use App\Http\Controllers\Admin\ProformaInvoiceController;
use App\Http\Controllers\Admin\WorkOrderController;
use Illuminate\Support\Facades\Route;
Route::middleware(['auth','verified','role:super-admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/work-orders',[WorkOrderController::class,'index'])->name('work-orders.index');
    Route::get('/work-orders/create',[WorkOrderController::class,'create'])->name('work-orders.create');
    Route::post('/work-orders',[WorkOrderController::class,'store'])->name('work-orders.store');
    Route::get('/work-orders/{workOrder}',[WorkOrderController::class,'show'])->name('work-orders.show');
    Route::post('/work-orders/{workOrder}/status',[WorkOrderController::class,'status'])->name('work-orders.status');
    Route::get('/proforma-invoices',[ProformaInvoiceController::class,'index'])->name('proforma-invoices.index');
    Route::get('/proforma-invoices/create',[ProformaInvoiceController::class,'create'])->name('proforma-invoices.create');
    Route::post('/proforma-invoices',[ProformaInvoiceController::class,'store'])->name('proforma-invoices.store');
    Route::get('/proforma-invoices/{proformaInvoice}',[ProformaInvoiceController::class,'show'])->name('proforma-invoices.show');
    Route::post('/proforma-invoices/{proformaInvoice}/status',[ProformaInvoiceController::class,'status'])->name('proforma-invoices.status');
});

