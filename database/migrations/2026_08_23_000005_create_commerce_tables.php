<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('wishlists',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->constrained()->cascadeOnDelete();$t->timestamps();$t->unique(['user_id','product_id']);});
  Schema::create('orders',function(Blueprint $t){$t->id();$t->uuid('uuid')->unique();$t->string('number')->unique();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->string('status')->default('pending_payment');$t->string('payment_status')->default('unpaid');$t->string('currency',3)->default('BDT');$t->decimal('subtotal',14,2);$t->decimal('discount_total',14,2)->default(0);$t->decimal('shipping_total',14,2)->default(0);$t->decimal('tax_total',14,2)->default(0);$t->decimal('grand_total',14,2);$t->string('customer_name');$t->string('email')->nullable();$t->string('phone',30);$t->string('country_code',2);$t->string('division')->nullable();$t->string('city');$t->string('postal_code')->nullable();$t->text('address');$t->text('notes')->nullable();$t->string('payment_method')->default('pending_selection');$t->string('shipping_method')->default('standard');$t->string('client_ip',45)->nullable();$t->timestamps();$t->index(['status','created_at']);});
  Schema::create('order_items',function(Blueprint $t){$t->id();$t->foreignId('order_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();$t->string('product_name');$t->string('variant_name')->nullable();$t->string('sku')->nullable();$t->decimal('quantity',12,2);$t->decimal('unit_price',14,2);$t->decimal('line_total',14,2);$t->json('snapshot')->nullable();$t->timestamps();});
 }
 public function down(): void {Schema::dropIfExists('order_items');Schema::dropIfExists('orders');Schema::dropIfExists('wishlists');}
};
