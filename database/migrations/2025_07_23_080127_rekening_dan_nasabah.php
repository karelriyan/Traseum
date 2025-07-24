<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekening', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_kk')->unique();
            $table->decimal('saldo', 15, 2)->default(0);
            $table->integer('poin')->default(0);
        });

        Schema::create('nasabah', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('jenis_kelamin');
            $table->date('tanggal_lahir');
            $table->string('no_telepon');
            $table->string('email')->unique();
            $table->string('alamat');
            $table->string('rt');
            $table->string('rw');

            $table->string('password');
            $table->foreignUuid('rekening_id')->constrained('rekening')->onDelete('cascade');
            $table->timestamps();
        });

        // Schema::create('riwayat_saldo', function (Blueprint $table) {
        //     $table->foreignUuid('rekening_id')->constrained('rekening')->onDelete('cascade');
        // });

        // Schema::create('riwayat_poin', function (Blueprint $table) {
        //     $table->foreignUuid('rekening_id')->constrained('rekening')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening');
        Schema::dropIfExists('nasabah');
    }
};
