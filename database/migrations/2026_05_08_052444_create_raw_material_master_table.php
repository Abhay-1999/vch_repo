<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
    {
        Schema::create('raw_material_master', function (Blueprint $table) {

            $table->id();

            // BASIC INFO
            $table->string('material_code', 50)->unique();
            $table->string('material_name', 150);
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('sub_category', 100)->nullable();
            $table->string('hsn_sac_code', 20)->nullable();

            // TAX & UOM
            $table->decimal('gst_rate', 5, 2)->nullable();
            $table->string('base_uom', 50)->nullable();
            $table->string('purchase_uom', 50)->nullable();
            $table->decimal('conversion_factor', 10, 2)->nullable();
            $table->string('recipe_uom', 50)->nullable();
            $table->decimal('recipe_conversion', 10, 2)->nullable();

            // PRICING
            $table->decimal('standard_cost', 12, 2)->nullable();
            $table->decimal('mrp', 12, 2)->nullable();

            // STOCK
            $table->decimal('min_stock_level', 10, 2)->nullable();
            $table->decimal('max_stock_level', 10, 2)->nullable();
            $table->decimal('reorder_quantity', 10, 2)->nullable();
            $table->integer('lead_time_days')->nullable();
            $table->integer('shelf_life_days')->nullable();
            $table->decimal('wastage_allowance', 5, 2)->nullable();

            // STORAGE / SUPPLIER
            $table->string('storage_type', 100)->nullable();
            $table->string('storage_location', 150)->nullable();
            $table->string('primary_supplier_id')->nullable();
            $table->string('alternate_supplier_id')->nullable();

            // STATUS
            $table->boolean('perishable')->default(0);
            $table->boolean('batch_tracked')->default(0);
            $table->boolean('active')->default(1);

            // EXTRA
            $table->date('created_on')->nullable();
            $table->date('last_updated')->nullable();
            $table->text('remarks')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_master');
    }
};
