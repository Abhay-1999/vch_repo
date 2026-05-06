<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_masters', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('supp_name', 150);
            $table->string('supp_code', 30)->nullable()->unique();
            $table->enum('supp_type', ['Local', 'Outside', 'Manufacturer', 'Wholesaler', 'Farmer', 'Import'])->nullable();
            $table->string('gst_no', 15)->nullable();

            // Food Supply Details
            $table->enum('supply_category', [
                'Vegetables', 'Fruits', 'Dairy', 'Meat', 'Seafood',
                'Dry Goods', 'Beverages', 'Bakery', 'Oils', 'Packaging', 'Cleaning', 'Other'
            ]);
            $table->string('fssai_no', 14)->nullable();
            $table->date('fssai_expiry')->nullable();
            $table->enum('delivery_days', ['Daily', 'Alternate', 'Weekly', 'On Order'])->nullable();
            $table->enum('delivery_slot', ['Early Morning', 'Morning', 'Afternoon', 'Evening'])->nullable();
            $table->decimal('min_order_value', 10, 2)->nullable()->default(0);
            $table->unsignedTinyInteger('lead_time_days')->nullable()->default(1);
            $table->text('items_supplied')->nullable();
            $table->enum('quality_grade', ['A', 'B', 'C'])->nullable();
            $table->enum('is_organic', ['Yes', 'No', 'Partial'])->default('No');

            // Address
            $table->string('supp_add1', 200)->nullable();
            $table->string('supp_add2', 200)->nullable();
            $table->string('market_name', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('pincode', 6)->nullable();
            $table->string('country', 100)->nullable()->default('India');

            // Contact
            $table->string('contact_person', 100)->nullable();
            $table->string('contact_no', 10);
            $table->string('alt_contact_no', 10)->nullable();
            $table->string('whatsapp_no', 10)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('pan_no', 10)->nullable();

            // Financial
            $table->decimal('opening_balance', 12, 2)->nullable()->default(0);
            $table->decimal('credit_limit', 12, 2)->nullable()->default(0);
            $table->unsignedSmallInteger('payment_terms')->nullable()->default(0);
            $table->enum('payment_mode', ['Cash', 'Cheque', 'NEFT', 'UPI', 'Credit'])->nullable();
            $table->decimal('discount_pct', 5, 2)->nullable()->default(0);

            // Bank
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 30)->nullable();
            $table->string('ifsc', 11)->nullable();
            $table->string('upi_id', 100)->nullable();

            // Additional
            $table->enum('status', ['Active', 'Inactive', 'Blacklisted'])->default('Active');
            $table->tinyInteger('supplier_rating')->nullable()->unsigned();
            $table->text('remark')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for frequent lookups
            $table->index('supp_name');
            $table->index('contact_no');
            $table->index('supply_category');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_masters');
    }
};