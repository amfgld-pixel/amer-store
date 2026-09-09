<?php
namespace Database\Seeders;use App\Models\MarketingSetting;use Illuminate\Database\Seeder;
class AmerMarketingSeeder extends Seeder{public function run():void{foreach(['ga4_measurement_id','meta_pixel_id','google_site_verification','facebook_domain_verification'] as $key)MarketingSetting::firstOrCreate(['key'=>$key],['value'=>null]);}}
