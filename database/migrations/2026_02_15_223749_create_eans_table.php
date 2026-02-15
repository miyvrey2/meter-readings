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
        Schema::create('eans', function (Blueprint $table) {
            $table->string('code', 18)->primary();
            $table->string('street');
            $table->string('house_number');
            $table->string('house_number_addition')->nullable();
            $table->string('city');
            $table->string('country')->default('Nederland');
            $table->string('network_operator');
            $table->decimal('cost_per_kwh_in_euro');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eans');
    }
};
