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
        Schema::create('report_catalogues', function (Blueprint $table) {
            $table->id();
            $table->string('report_code')->unique();
            $table->string('report_name');
            $table->string('category');
            $table->string('frequency');
            $table->text('description')->nullable();
            $table->text('kpis_metrics')->nullable();
            $table->string('source_tables')->nullable();
            $table->string('primary_filter')->nullable();
            $table->string('default_sort')->nullable();
            $table->string('output_format')->nullable();
            $table->string('priority')->default('Medium');
            $table->boolean('status')->default(1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_catalogues');
    }
};
