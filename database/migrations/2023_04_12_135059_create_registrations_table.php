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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('stock_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('register_name')->nullable();
            $table->smallInteger('number_plate',20)->nullable();
            $table->string('regional_code',10)->nullable();
            $table->string('currency',20)->nullable()->default('MMK');
            $table->decimal('rta_tax',12,2)->nullable();
            $table->date('received_at')->nullable();
            $table->date('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
