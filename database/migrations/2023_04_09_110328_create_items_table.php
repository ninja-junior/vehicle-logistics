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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->nullable()->constrained()->cascadeOnDelete();           
            $table->foreignId('import_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained()->nullOnDelete();      
            $table->foreignId('import_license_id')->nullable()->constrained()->nullOnDelete();                  
            $table->foreignId('customs_id')->nullable()->constrained()->nullOnDelete();                  
            $table->string('tracking_number',30)->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
