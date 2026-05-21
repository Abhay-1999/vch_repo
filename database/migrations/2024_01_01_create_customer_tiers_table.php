<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('tier_id')->unique();
            $table->string('tier_name');
            $table->decimal('min_lifetime_spend', 12, 2)->default(0);
            $table->decimal('max_lifetime_spend', 12, 2)->nullable();
            $table->decimal('auto_discount_percent', 5, 2)->default(0);
            $table->decimal('discount_cap', 10, 2)->default(0);
            $table->decimal('birthday_bonus_percent', 5, 2)->default(0);
            $table->decimal('reward_points_per_100', 5, 2)->default(0);
            $table->string('linked_discount_code')->nullable();
            $table->string('color')->nullable();
            $table->string('status')->default('Active');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_tiers');
    }
};