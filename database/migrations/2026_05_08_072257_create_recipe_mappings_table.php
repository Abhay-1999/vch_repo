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
        Schema::create('recipe_mappings', function (Blueprint $table) {
            $table->id();

            $table->string('recipe_id');
            $table->string('item_code');
            $table->string('item_name');

            $table->decimal('selling_price', 10, 2)->nullable();
            $table->integer('standard_yield')->nullable();

            $table->string('material_code')->nullable();
            $table->string('material_name')->nullable();

            $table->decimal('qty_per_serving', 10, 2)->nullable();
            $table->string('recipe_uom')->nullable();
            $table->decimal('qty_in_base_uom', 10, 2)->nullable();

            $table->decimal('cost_per_base_uom', 10, 2)->nullable();
            $table->decimal('ingredient_cost', 10, 2)->nullable();

            $table->decimal('wastage_allowance', 5, 2)->nullable();
            $table->decimal('effective_cost', 10, 2)->nullable();

            $table->boolean('active')->default(1);

            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();

            $table->string('created_by')->nullable();
            $table->string('approved_by')->nullable();

            $table->text('remarks')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_mappings');
    }
};
