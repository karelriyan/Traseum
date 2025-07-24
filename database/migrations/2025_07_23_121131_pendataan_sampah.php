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
        Schema::create('pendataan_sampah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('jenis_sampah');
            $table->string('harga_sampah');
            $table->string('poin_sampah');

            $table->integer('total_berat');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendataan_sampah');
    }
};
