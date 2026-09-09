<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_slides', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('subtitle')->nullable()->change();
            $table->string('title_font')->default('Manrope');
            $table->unsignedSmallInteger('title_size_desktop')->default(72);
            $table->unsignedSmallInteger('title_size_tablet')->default(52);
            $table->unsignedSmallInteger('title_size_mobile')->default(34);
            $table->unsignedSmallInteger('subtitle_size_desktop')->default(20);
            $table->unsignedSmallInteger('subtitle_size_tablet')->default(18);
            $table->unsignedSmallInteger('subtitle_size_mobile')->default(15);
            $table->string('title_color', 7)->default('#161616');
            $table->string('subtitle_color', 7)->default('#161616');
            $table->string('font_weight', 3)->default('400');
            $table->string('text_alignment', 10)->default('left');
            $table->string('text_position', 10)->default('middle');
            $table->decimal('line_height', 3, 2)->default(1.00);
            $table->decimal('letter_spacing', 5, 2)->default(0.00);
            $table->string('text_shadow', 10)->default('none');
            $table->string('overlay_color', 7)->default('#FFFFFF');
            $table->unsignedTinyInteger('overlay_opacity')->default(25);
            $table->string('button_font')->default('Inter');
            $table->unsignedSmallInteger('button_size')->default(16);
            $table->string('button_text_color', 7)->default('#FFFFFF');
            $table->string('button_background_color', 7)->default('#161616');
        });
    }

    public function down(): void
    {
        Schema::table('store_slides', function (Blueprint $table) {
            $table->dropColumn([
                'title_font', 'title_size_desktop', 'title_size_tablet', 'title_size_mobile',
                'subtitle_size_desktop', 'subtitle_size_tablet', 'subtitle_size_mobile', 'title_color',
                'subtitle_color', 'font_weight', 'text_alignment', 'text_position', 'line_height',
                'letter_spacing', 'text_shadow', 'overlay_color', 'overlay_opacity', 'button_font',
                'button_size', 'button_text_color', 'button_background_color',
            ]);
        });
    }
};
