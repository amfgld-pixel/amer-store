<?php
namespace Database\Seeders;use App\Models\ShippingZone;use Illuminate\Database\Seeder;
class AmerShippingSeeder extends Seeder{public function run():void{ShippingZone::firstOrCreate(['name'=>'Dhaka City'],['country_code'=>'BD','city'=>'Dhaka','base_rate'=>80,'currency'=>'BDT','is_active'=>true]);ShippingZone::firstOrCreate(['name'=>'Outside Dhaka'],['country_code'=>'BD','city'=>null,'base_rate'=>130,'currency'=>'BDT','is_active'=>true]);}}
