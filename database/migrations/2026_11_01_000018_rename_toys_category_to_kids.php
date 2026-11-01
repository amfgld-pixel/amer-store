<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        $toys = DB::table('categories')
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->where('slug', 'toys')->orWhere('name', 'Toys');
            })
            ->first();

        $kids = DB::table('categories')
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->where('slug', 'kids')->orWhere('name', 'Kids');
            })
            ->first();

        if (!$toys) {
            return;
        }

        if (!$kids) {
            DB::table('categories')->where('id', $toys->id)->update([
                'name' => 'Kids',
                'name_bn' => 'শিশু',
                'slug' => 'kids',
                'updated_at' => now(),
            ]);

            return;
        }

        if (Schema::hasTable('category_product')) {
            $productIds = DB::table('category_product')
                ->where('category_id', $toys->id)
                ->pluck('product_id');

            foreach ($productIds as $productId) {
                DB::table('category_product')->insertOrIgnore([
                    'category_id' => $kids->id,
                    'product_id' => $productId,
                ]);
            }

            DB::table('category_product')->where('category_id', $toys->id)->delete();
        }

        DB::table('categories')->where('parent_id', $toys->id)->update([
            'parent_id' => $kids->id,
            'updated_at' => now(),
        ]);

        DB::table('categories')->where('id', $toys->id)->delete();
    }

    public function down(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        $toysExists = DB::table('categories')->whereNull('deleted_at')->where('slug', 'toys')->exists();

        if (!$toysExists) {
            DB::table('categories')->whereNull('deleted_at')->where('slug', 'kids')->update([
                'name' => 'Toys',
                'name_bn' => 'খেলনা',
                'slug' => 'toys',
                'updated_at' => now(),
            ]);
        }
    }
};
