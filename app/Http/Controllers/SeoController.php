<?php
namespace App\Http\Controllers;use App\Models\Category;use App\Models\Product;
class SeoController extends Controller{public function sitemap(){return response()->view('seo.sitemap',['products'=>Product::where('status','active')->get(['slug','updated_at']),'categories'=>Category::where('is_active',true)->get(['slug','updated_at'])])->header('Content-Type','application/xml');}public function robots(){return response("User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /checkout\nSitemap: ".url('/sitemap.xml')."\n",200,['Content-Type'=>'text/plain']);}}
