<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $t) {
            $t->id(); $t->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $t->string('name'); $t->string('name_bn')->nullable(); $t->string('slug')->unique();
            $t->text('description')->nullable(); $t->text('description_bn')->nullable();
            $t->string('image_path')->nullable(); $t->unsignedInteger('sort_order')->default(0);
            $t->string('meta_title')->nullable(); $t->text('meta_description')->nullable();
            $t->boolean('is_active')->default(true); $t->timestamps(); $t->softDeletes();
        });
        Schema::create('brands', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('name_bn')->nullable(); $t->string('slug')->unique();
            $t->string('logo_path')->nullable(); $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0); $t->boolean('is_active')->default(true);
            $t->timestamps(); $t->softDeletes();
        });
        Schema::create('attributes', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('name_bn')->nullable(); $t->string('slug')->unique();
            $t->string('display_type')->default('select'); $t->unsignedInteger('sort_order')->default(0); $t->timestamps();
        });
        Schema::create('attribute_values', function (Blueprint $t) {
            $t->id(); $t->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            $t->string('value'); $t->string('value_bn')->nullable(); $t->string('slug');
            $t->string('color_hex', 7)->nullable(); $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps(); $t->unique(['attribute_id','slug']);
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id(); $t->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('name_bn')->nullable(); $t->string('slug')->unique();
            $t->string('product_type')->default('variable'); $t->text('short_description')->nullable();
            $t->text('short_description_bn')->nullable(); $t->longText('description')->nullable();
            $t->longText('description_bn')->nullable(); $t->string('status')->default('draft');
            $t->boolean('is_featured')->default(false); $t->unsignedInteger('sort_order')->default(0);
            $t->string('meta_title')->nullable(); $t->text('meta_description')->nullable();
            $t->timestamps(); $t->softDeletes();
        });
        Schema::create('category_product', function (Blueprint $t) {
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->primary(['category_id','product_id']);
        });
        Schema::create('product_variants', function (Blueprint $t) {
            $t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('name')->nullable(); $t->string('sku')->unique(); $t->string('barcode')->nullable()->unique();
            $t->decimal('cost_price',14,2)->default(0); $t->decimal('price',14,2);
            $t->decimal('compare_at_price',14,2)->nullable(); $t->decimal('weight',10,3)->nullable();
            $t->string('weight_unit',5)->default('kg'); $t->boolean('track_inventory')->default(true);
            $t->boolean('is_active')->default(true); $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps(); $t->softDeletes();
        });
        Schema::create('attribute_value_product_variant', function (Blueprint $t) {
            $t->foreignId('attribute_value_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
            $t->primary(['attribute_value_id','product_variant_id']);
        });
        Schema::create('product_images', function (Blueprint $t) {
            $t->id(); $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $t->string('path'); $t->string('alt_text')->nullable(); $t->boolean('is_primary')->default(false);
            $t->unsignedInteger('sort_order')->default(0); $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('product_images'); Schema::dropIfExists('attribute_value_product_variant');
        Schema::dropIfExists('product_variants'); Schema::dropIfExists('category_product');
        Schema::dropIfExists('products'); Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes'); Schema::dropIfExists('brands'); Schema::dropIfExists('categories');
    }
};
