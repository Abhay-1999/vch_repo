<?php
// ═══════════════════════════════════════════════════════════════
// MIGRATION 1: create_grn_headers_table.php
// ═══════════════════════════════════════════════════════════════
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grn_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grn_header_id')->constrained('grn_headers')->cascadeOnDelete();
            $table->string('grn_no', 50)->index();
            // Material
            $table->string('material_code', 50);
            $table->string('material_name');
            $table->string('batch_lot_no', 100)->nullable();
            $table->date('mfg_date')->nullable();
            $table->date('expiry_date')->nullable();
            // Quantity
            $table->decimal('qty_purchase_uom', 15, 4);
            $table->string('purchase_uom', 20);
            $table->decimal('conversion_factor', 15, 4)->default(1);
            $table->decimal('qty_base_uom', 15, 4)->default(0);
            $table->string('base_uom', 20)->nullable();
            // Pricing
            $table->decimal('rate_per_purchase_uom', 15, 2);
            $table->decimal('taxable_value', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('net_taxable_value', 15, 2)->default(0);
            $table->decimal('gst_rate', 5, 2)->default(0);
            $table->decimal('cgst', 15, 2)->default(0);
            $table->decimal('sgst', 15, 2)->default(0);
            $table->decimal('igst', 15, 2)->default(0);
            $table->decimal('total_gst', 15, 2)->default(0);
            $table->decimal('other_charges', 15, 2)->default(0);
            $table->decimal('round_off', 10, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('effective_cost_per_base_uom', 15, 4)->nullable();
            // QC
            $table->enum('quality_check', ['Pass', 'Fail', 'Pending'])->default('Pending');
            $table->decimal('accepted_qty_base_uom', 15, 4)->nullable();
            $table->decimal('rejected_qty_base_uom', 15, 4)->nullable();
            $table->string('rejection_reason', 500)->nullable();
            // Payment (row-level override)
            $table->enum('payment_status', ['Unpaid', 'Partially Paid', 'Paid'])->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_reference', 100)->nullable();
            $table->string('remark', 500)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('grn_items'); }
};