<?php
namespace Database\Seeders;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
class AmerCatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['Products View','products.view','Catalog'],['Products Manage','products.manage','Catalog'],['Categories View','categories.view','Catalog'],['Categories Manage','categories.manage','Catalog'],['Brands Manage','brands.manage','Catalog'],['Attributes Manage','attributes.manage','Catalog']] as $p) {
            $permission=Permission::firstOrCreate(['slug'=>$p[1]],['name'=>$p[0],'group'=>$p[2]]);
            Role::where('slug','super-admin')->first()?->permissions()->syncWithoutDetaching([$permission->id]);
        }
        Brand::firstOrCreate(['slug'=>'amer'],['name'=>'AMER','name_bn'=>'আমের','is_active'=>true]);
        foreach ([['clothing','Clothing','পোশাক'],['men','Men','পুরুষ'],['women','Women','নারী'],['accessories','Accessories','এক্সেসরিজ'],['toys','Toys','খেলনা'],['new-arrivals','New Arrivals','নতুন পণ্য']] as $i=>$c) Category::firstOrCreate(['slug'=>$c[0]],['name'=>$c[1],'name_bn'=>$c[2],'sort_order'=>$i,'is_active'=>true]);
        foreach ([['size','Size','সাইজ','button'],['color','Color','রং','color']] as $i=>$a) Attribute::firstOrCreate(['slug'=>$a[0]],['name'=>$a[1],'name_bn'=>$a[2],'display_type'=>$a[3],'sort_order'=>$i]);
    }
}
