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
        Schema::create('umkm', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('nasabah_id')
                ->constrained('nasabah')
                ->unique()
                ->index()
                ->name('fk_umkm_nasabah');
            $table->string('nama_umkm', 255);
            $table->text('deskripsi');
            $table->timestamps();
        });

        Schema::create('postingan_umkm', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('umkm_id')
                ->constrained('umkm')
                ->index()
                ->name('fk_postingan_umkm_umkm');
            $table->string('judul_postingan', 255);
            $table->decimal('harga', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postingan_umkm');
        Schema::dropIfExists('umkm');
    }
};
