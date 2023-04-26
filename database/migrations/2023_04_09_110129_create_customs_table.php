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
        Schema::create('customs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ro_number',20);
            $table->date('started_at')->nullable();
            $table->date('ro_date')->nullable();
            $table->string('currency')->nullable()->default('usd');
            $table->decimal('ex_rate',10,2)->nullable();
            $table->decimal('total_taxes',10,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customs');
    }
};
