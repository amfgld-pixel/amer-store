<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([AmerFoundationSeeder::class,AmerCatalogSeeder::class,AmerInventorySeeder::class]);
    }
}
