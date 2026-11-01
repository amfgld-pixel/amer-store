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

        $kids = DB::table('categories')->whereNull('deleted_at')->where('slug', 'kids')->first();
        $accessories = DB::table('categories')->whereNull('deleted_at')->where('slug', 'accessories')->first();

        if (!$kids || !$accessories) {
            return;
        }

        $kidsOrder = (int) $kids->sort_order;
        $accessoriesOrder = (int) $accessories->sort_order;

        if ($kidsOrder === $accessoriesOrder) {
            DB::table('categories')->where('id', $kids->id)->update(['sort_order' => $kidsOrder]);
            DB::table('categories')->where('id', $accessories->id)->update(['sort_order' => $accessoriesOrder + 1]);

            return;
        }

        DB::table('categories')->where('id', $kids->id)->update([
            'sort_order' => min($kidsOrder, $accessoriesOrder),
            'updated_at' => now(),
        ]);

        DB::table('categories')->where('id', $accessories->id)->update([
            'sort_order' => max($kidsOrder, $accessoriesOrder),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        $kids = DB::table('categories')->whereNull('deleted_at')->where('slug', 'kids')->first();
        $accessories = DB::table('categories')->whereNull('deleted_at')->where('slug', 'accessories')->first();

        if (!$kids || !$accessories) {
            return;
        }

        $kidsOrder = (int) $kids->sort_order;
        $accessoriesOrder = (int) $accessories->sort_order;

        DB::table('categories')->where('id', $accessories->id)->update(['sort_order' => min($kidsOrder, $accessoriesOrder), 'updated_at' => now()]);
        DB::table('categories')->where('id', $kids->id)->update(['sort_order' => max($kidsOrder, $accessoriesOrder), 'updated_at' => now()]);
    }
};
