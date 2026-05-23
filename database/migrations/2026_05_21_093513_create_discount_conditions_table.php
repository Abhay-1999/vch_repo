<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
 public function up(): void
    {
        Schema::create('discount_conditions', function (Blueprint $table) {

            // AUTO INCREMENT PRIMARY KEY
            $table->bigIncrements('condition_id');

            // DISCOUNT CODE
            $table->string('discount_id', 12);

            // CONDITION DETAILS
            $table->string('condition_type', 30);
            $table->string('operator', 10);
            $table->string('value', 120);

            // OPTIONAL NOTE
            $table->string('note', 160)->nullable();

            // TIMESTAMPS
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
