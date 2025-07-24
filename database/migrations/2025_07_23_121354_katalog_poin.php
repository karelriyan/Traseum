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
        Schema::create('katalog_poin', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('nama_barang');
            $table->string('deskripsi_barang');
            $table->string('kategori_barang');
            $table->string('foto_barang');
            $table->integer('harga');
        });

        Schema::create('transaksi_poin', function (Blueprint $table) {
            $table->primary('katalog_poin_id', 'rekening_id');
            $table->integer('pengurangan_poin');

            $table->foreignUuid('katalog_poin_id')->constrained('katalog_poin');
            $table->foreignUuid('rekening_id')->constrained('rekening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalog_poin');
        Schema::dropIfExists('transaksi_poin');
    }
};
