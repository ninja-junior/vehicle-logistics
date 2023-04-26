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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('code', 20)->nullable();
            $table->string('country', 60)->nullable();
            $table->string('city', 85)->nullable();
            $table->string('address',200)->nullable();
            $table->string('type',50)->nullable();

            $table->string('full_location')->virtualAs("CONCAT(name, ' : ', city)");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
