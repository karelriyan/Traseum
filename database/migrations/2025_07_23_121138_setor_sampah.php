<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\AcceptHeader;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setor_sampah', function (Blueprint $table) {
            $table->primary('pendataan_sampah_id', 'rekening_id');
            $table->string('berat'); //berat sampahnya

            $table->foreignUuid('pendataan_sampah_id')->constrained('pendataan_sampah'); //jenis sampah apa
            $table->foreignUuid('rekening_id')->constrained('rekening'); //masuk rekening siapa

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setor_sampah');
    }
};
