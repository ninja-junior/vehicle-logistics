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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('modell_id')->nullable()->constrained()->nullOnDelete();
            $table->string('number', 32)->unique();
            $table->string('vin',17)->unique();
            $table->smallInteger('engine_power')->nullable();
            $table->smallInteger('model_year')->nullable();
            $table->string('type',10)->nullable()->default('pc');
            $table->string('group',50)->nullable()->default('saloon');            
            $table->string('country',60)->nullable();
            $table->string('currency',30)->nullable()->default("USD");
            $table->decimal('cif_price',12, 2)->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('stock_vin')->virtualAs("CONCAT(number, ' : ', vin)");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
