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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('pol_id')->nullable();
            $table->unsignedBigInteger('pod_id')->nullable();
            $table->string('name',50);
            $table->string('voy',10);
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            $table->string('vessel')->virtualAs("CONCAT(name, ' - ', voy)");
            $table->unique(['name','voy']);
            $table->timestamps();
            $table->foreign('pol_id')->references('id')->on('locations');
            $table->foreign('pod_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
