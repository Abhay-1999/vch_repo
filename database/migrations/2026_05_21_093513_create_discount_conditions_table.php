<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
     public function up(): void
    {
       Schema::create('discount_conditions', function (Blueprint $table) {
    $table->id(); // PRIMARY KEY AUTO INCREMENT

    $table->string('condition_group_id'); // CND-0001 (GROUP ID)

    $table->string('discount_id', 12);
    $table->string('condition_type', 30);
    $table->string('operator', 10);
    $table->string('value', 120);
    $table->string('note', 160)->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_conditions');
    }
};
