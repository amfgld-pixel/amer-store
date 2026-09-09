<?php
use App\Http\Controllers\NewsletterController;use App\Http\Controllers\SeoController;use Illuminate\Support\Facades\Route;
Route::get('/sitemap.xml',[SeoController::class,'sitemap'])->name('seo.sitemap');Route::get('/robots.txt',[SeoController::class,'robots'])->name('seo.robots');Route::post('/newsletter',[NewsletterController::class,'store'])->middleware('throttle:5,1')->name('newsletter.store');
