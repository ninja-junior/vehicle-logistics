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
        Schema::create('transportations', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('stock_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('origin_id')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->string('booking_number',20)->nullable();
            $table->string('carrier_number',20)->nullable();
            $table->string('driver_name',50)->nullable();
            $table->dateTime('depature_time')->nullable();
            $table->dateTime('arrival_time')->nullable();
            $table->string('received_by')->nullable();
            $table->string('photo')->nullable();
            $table->text('route_description',300)->nullable();
            $table->text('note',500)->nullable();
            $table->timestamps();
            $table->foreign('origin_id')->references('id')->on('locations')->nullOnDelete();
            $table->foreign('destination_id')->references('id')->on('locations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportations');
    }
};
