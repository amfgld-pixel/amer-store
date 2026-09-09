<?php

namespace Database\Seeders;

use App\Models\InventoryLocation;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AmerInventorySeeder extends Seeder
{
    public function run(): void
    {
        $location=InventoryLocation::firstOrCreate(['code'=>'MAIN'],['name'=>'Main Stock','name_bn'=>'মূল স্টক','type'=>'warehouse','is_default'=>true,'is_active'=>true]);
        $permissions=['inventory.view','inventory.adjust','stock-ledger.view','suppliers.manage','purchases.view','purchases.create','purchases.approve','purchases.receive'];
        foreach($permissions as $slug){ Permission::firstOrCreate(['slug'=>$slug],['name'=>ucwords(str_replace(['.','-'],' ',$slug)),'group'=>'Inventory']); }
        $super=Role::where('slug','super-admin')->first(); if($super){$super->permissions()->syncWithoutDetaching(Permission::whereIn('slug',$permissions)->pluck('id'));}
        $manager=Role::firstOrCreate(['slug'=>'inventory-manager'],['name'=>'Inventory Manager']);
        $manager->permissions()->syncWithoutDetaching(Permission::whereIn('slug',$permissions)->pluck('id'));
    }
}
