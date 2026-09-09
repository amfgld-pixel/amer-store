<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Pack004StorefrontTest extends TestCase
{
    use RefreshDatabase;
    public function test_homepage_is_amer_storefront(): void { $this->seed(); $this->get('/')->assertOk()->assertSee('Quiet confidence.')->assertSee('AMER'); }
    public function test_active_product_is_publicly_visible(): void { $this->seed(); $product=Product::create(['name'=>'AMER Test','slug'=>'amer-test','product_type'=>'variable','status'=>'active']); $this->get(route('product.show',$product))->assertOk()->assertSee('AMER Test'); }
    public function test_inactive_product_is_not_public(): void { $this->seed(); $product=Product::create(['name'=>'Hidden','slug'=>'hidden','product_type'=>'variable','status'=>'draft']); $this->get(route('product.show',$product))->assertNotFound(); }
    public function test_collection_page_loads(): void { $this->seed(); $category=Category::firstOrFail(); $this->get(route('collection.show',$category))->assertOk()->assertSee($category->name); }
}
