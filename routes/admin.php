<?php

use App\Http\Controllers\Admin\CompanySettingsController;
use App\Http\Controllers\Admin\ContentPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsHubController;
use App\Http\Controllers\Admin\StoreDesignController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::middleware('role:super-admin')->group(function () {
        Route::get('/settings', SettingsHubController::class)->name('settings.index');
        Route::get('/settings/company/{company}', [CompanySettingsController::class, 'edit'])->name('settings.company.edit');
        Route::put('/settings/company/{company}', [CompanySettingsController::class, 'update'])->name('settings.company.update');
        Route::get('/settings/store-design', [StoreDesignController::class, 'edit'])->name('settings.store-design.edit');
        Route::put('/settings/store-design', [StoreDesignController::class, 'update'])->name('settings.store-design.update');
        Route::post('/settings/store-slides', [StoreDesignController::class, 'storeSlide'])->name('settings.slides.store');
        Route::post('/settings/store-slides/bulk', [StoreDesignController::class, 'storeBulkSlides'])->name('settings.slides.bulk');
        Route::put('/settings/store-slides/{slide}', [StoreDesignController::class, 'updateSlide'])->name('settings.slides.update');
        Route::delete('/settings/store-slides/{slide}', [StoreDesignController::class, 'destroySlide'])->name('settings.slides.destroy');
        Route::resource('pages', ContentPageController::class)->except('show');
    });
});
