<?php
// ═══════════════════════════════════════════════════════════════
// MIGRATION 1: create_grn_headers_table.php
// ═══════════════════════════════════════════════════════════════
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void {
        Schema::create('grn_headers', function (Blueprint $table) {
            $table->id();
            $table->string('grn_no', 50)->unique();
            $table->date('grn_date');
            $table->string('po_no', 50);
            $table->string('invoice_no', 100);
            $table->date('invoice_date');
            $table->string('supplier_id', 50);
            $table->string('supplier_name');
            $table->string('storage_location');
            $table->string('received_by', 100);
            $table->string('verified_by', 100);
            // Aggregated totals
            $table->decimal('total_taxable_value', 15, 2)->default(0);
            $table->decimal('total_gst_amount', 15, 2)->default(0);
            $table->decimal('total_other_charges', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->unsignedSmallInteger('item_count')->default(0);
            // Payment (header-level)
            $table->enum('payment_status', ['Unpaid', 'Partially Paid', 'Paid'])->default('Unpaid');
            $table->date('payment_date')->nullable();
            $table->string('payment_reference', 100)->nullable();
            $table->string('remark', 500)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('grn_headers'); }
};
 
 