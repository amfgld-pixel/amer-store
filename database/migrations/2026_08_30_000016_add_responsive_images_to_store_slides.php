<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_slides', function (Blueprint $table) {
            $table->string('image_path_tablet')->nullable();
            $table->string('image_path_mobile')->nullable();
            $table->unsignedInteger('image_original_width')->nullable();
            $table->unsignedInteger('image_original_height')->nullable();
            $table->string('image_fit', 12)->default('contain');
            $table->unsignedTinyInteger('image_focal_x')->default(50);
            $table->unsignedTinyInteger('image_focal_y')->default(50);
        });
    }

    public function down(): void
    {
        Schema::table('store_slides', function (Blueprint $table) {
            $table->dropColumn(['image_path_tablet', 'image_path_mobile', 'image_original_width',
                'image_original_height', 'image_fit', 'image_focal_x', 'image_focal_y']);
        });
    }
};
