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
        Schema::rename('eans', 'connections');

        Schema::table('connections', function (Blueprint $table) {
            $table->renameColumn('code', 'ean_code');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->renameColumn('ean_code', 'code');
        });

        Schema::rename('connections', 'eans');
    }
};
