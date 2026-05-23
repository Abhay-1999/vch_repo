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
        Schema::create('discount_approval_rules', function (Blueprint $table) {
    
            $table->id();
    
            $table->string('rule_id')->unique();
    
            $table->string('condition');
    
            $table->string('threshold');
    
            $table->string('approval_required');
    
            $table->string('otp_password')->nullable();
    
            $table->boolean('audit_log')->default(1);
    
            $table->string('email_alert_to')->nullable();
    
            $table->text('remarks')->nullable();
    
            $table->boolean('status')->default(1);
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_approval_rules');
    }
};
