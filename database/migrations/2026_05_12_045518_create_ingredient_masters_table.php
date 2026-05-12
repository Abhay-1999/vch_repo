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
    Schema::create('ingredient_masters', function (Blueprint $table) {

        $table->id();

        // Basic Details
        $table->string('ingredient_code')->unique();
        $table->string('ingredient_name');

        $table->string('category')->nullable();

        /*
        |--------------------------------------------------------------------------
        | Purchase Details
        |--------------------------------------------------------------------------
        */

        $table->string('purchase_uom')->nullable();

        $table->decimal('purchase_qty', 18, 3)->default(0);

        $table->decimal('purchase_cost', 18, 2)->default(0);

        // Example: 35.000000
        $table->decimal('cost_per_purchase_unit', 18, 6)->default(0);

        /*
        |--------------------------------------------------------------------------
        | Base Conversion
        |--------------------------------------------------------------------------
        */

        $table->string('base_uom')->nullable();

        // Example: 1000.000
        $table->decimal('conversion_to_base', 18, 3)->default(1);

        // Example: 0.035000000
        $table->decimal('gross_cost_per_base_unit', 18, 9)->default(0);

        /*
        |--------------------------------------------------------------------------
        | Yield & Costing
        |--------------------------------------------------------------------------
        */

        // Example: 92.00
        $table->decimal('yield_percent', 5, 2)->default(100);

        // Example: 0.038043478
        $table->decimal('net_cost_per_base_unit', 18, 9)->default(0);

        // Example: 5.00
        $table->decimal('wastage_allowance_percent', 5, 2)->default(0);

        // Example: 0.039945652
        $table->decimal('costing_rate', 18, 9)->default(0);

        /*
        |--------------------------------------------------------------------------
        | Other Details
        |--------------------------------------------------------------------------
        */

        $table->string('supplier')->nullable();

        $table->text('remarks')->nullable();

        $table->timestamp('last_updated')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_masters');
    }
};