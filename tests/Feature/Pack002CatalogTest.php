<?php
namespace Tests\Feature;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class Pack002CatalogTest extends TestCase
{
    use RefreshDatabase;
    protected function superAdmin(): User
    {
        $this->seed(\Database\Seeders\AmerFoundationSeeder::class);
        $this->seed(\Database\Seeders\AmerCatalogSeeder::class);
        return User::where('email','admin@amer.com')->firstOrFail();
    }
    public function test_catalog_seed_data_exists(): void
    {
        $this->superAdmin(); $this->assertDatabaseHas('categories',['slug'=>'clothing']); $this->assertDatabaseHas('brands',['slug'=>'amer']);
    }
    public function test_super_admin_can_create_category(): void
    {
        $this->actingAs($this->superAdmin())->post('/admin/categories',['name'=>'Footwear','name_bn'=>'জুতা','slug'=>'footwear','sort_order'=>8,'is_active'=>1])->assertRedirect();
        $this->assertDatabaseHas('categories',['slug'=>'footwear']);
    }
    public function test_super_admin_can_create_product_with_variant(): void
    {
        $user=$this->superAdmin(); $category=Category::firstOrFail();
        $this->actingAs($user)->post('/admin/products',['name'=>'AMER Essential Tee','slug'=>'amer-essential-tee','product_type'=>'variable','status'=>'active','is_featured'=>1,'sort_order'=>0,'category_ids'=>[$category->id],'variants'=>[['name'=>'Black / M','sku'=>'AMER-TEE-BLK-M','barcode'=>'100001','cost_price'=>500,'price'=>1200,'compare_at_price'=>1500,'weight'=>0.25,'is_active'=>1]]])->assertRedirect();
        $this->assertDatabaseHas('products',['slug'=>'amer-essential-tee']); $this->assertDatabaseHas('product_variants',['sku'=>'AMER-TEE-BLK-M']);
    }
}
