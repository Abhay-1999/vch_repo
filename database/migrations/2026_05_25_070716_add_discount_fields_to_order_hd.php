<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_hd', function ($table) {
    
            $table->string('discount_code',20)->nullable();
    
            $table->string('discount_name',100)->nullable();
    
            $table->decimal(
                'discount_amount',
                10,
                2
            )->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_hd', function (Blueprint $table) {
            //
        });
    }
};
