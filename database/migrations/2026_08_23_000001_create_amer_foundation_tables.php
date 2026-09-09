<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('facebook_url')->nullable();
            $table->text('address')->nullable();
            $table->text('address_bn')->nullable();
            $table->string('country_code', 2)->default('BD');
            $table->string('currency_code', 3)->default('BDT');
            $table->string('timezone')->default('Asia/Dhaka');
            $table->string('locale', 5)->default('en');
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique();
            $table->boolean('is_system')->default(false); $table->timestamps();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique();
            $table->string('group')->nullable(); $table->timestamps();
        });
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event', 40); $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->jsonb('old_values')->nullable(); $table->jsonb('new_values')->nullable();
            $table->ipAddress('ip_address')->nullable(); $table->text('user_agent')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs'); Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role'); Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles'); Schema::dropIfExists('companies');
    }
};
