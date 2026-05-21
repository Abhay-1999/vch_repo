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
        Schema::create('discount_master', function (Blueprint $table) {

            $table->id();
            $table->string('discount_id', 12)->unique();
            $table->string('name', 80);
            $table->string('type', 20);
            $table->decimal('value', 10, 2)->default(0);
            $table->string('unit', 4)->nullable();
            $table->decimal('max_cap', 10, 2)->default(0);
            $table->decimal('min_bill', 10, 2)->default(0);
            $table->string('applies_to', 80)->default('BILL');
            $table->tinyInteger('stackable')->default(0);
            $table->tinyInteger('auto_apply')->default(0);
            $table->tinyInteger('approval_req')->default(0);
            $table->string('approval_level', 20)->default('NONE');
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->string('active_days', 50)->default('ALL');
            $table->string('active_hours', 30)->default('ALL');
            $table->string('channel', 40)->default('ALL');
            $table->string('outlet', 40)->default('ALL');
            $table->string('status', 10)->default('Active');
            $table->string('created_by', 40)->nullable();
            $table->dateTime('created_on')->nullable();
            $table->string('remarks', 160)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_master');
    }
};
