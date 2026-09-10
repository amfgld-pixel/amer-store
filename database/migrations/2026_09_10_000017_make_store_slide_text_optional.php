<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_slides', function (Blueprint $table) {
            $table->text('title')->nullable()->change();
            $table->text('subtitle')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Existing slides may contain null text, so rollback intentionally keeps both fields nullable.
    }
};
