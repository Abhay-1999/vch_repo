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
        Schema::create('yield_wastage_test_dt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_hd_id');
            $table->string('ingredient_code');
            $table->string('ingredient_name');
        
            $table->decimal('ap_weight',10,2)->default(0);
            $table->decimal('trim_loss',10,2)->default(0);
            $table->decimal('cooking_loss',10,2)->default(0);
            $table->decimal('ep_weight',10,2)->default(0);
            $table->decimal('yield_percent',10,2)->default(0);
        
            $table->decimal('ap_cost',10,2)->default(0);
            $table->decimal('ap_cost_per_gm',10,4)->default(0);
            $table->decimal('ep_cost_per_gm',10,4)->default(0);
        
            $table->timestamps();
        
            $table->foreign('test_hd_id')
                ->references('id')
                ->on('yield_wastage_test_hd')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yield_wastage_test_dt');
    }
};
