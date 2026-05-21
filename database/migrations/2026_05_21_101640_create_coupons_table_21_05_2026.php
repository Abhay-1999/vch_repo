<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
        
            $table->string('coupon_code')->unique();
            $table->string('linked_discount_code')->nullable();
            $table->string('discount_name')->nullable();
        
            $table->integer('usage_limit')->default(0);
            $table->integer('used_count')->default(0);
            $table->integer('remaining')->default(0);
        
            $table->boolean('once_per_customer')->default(false);
        
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
        
            $table->decimal('min_bill', 10, 2)->default(0);
        
            $table->string('status')->default('Active'); // Active / Expired / Disabled
            $table->string('channel')->default('All');   // All / Online / Dine-In etc
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons_table_21_05_2026');
    }
};
