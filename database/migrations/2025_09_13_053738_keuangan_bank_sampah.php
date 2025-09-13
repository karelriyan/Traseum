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
        // Membuat tabel referensi terlebih dahulu
        Schema::create('sumber_pemasukan', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama_pemasukan');
            $table->timestamps(); // Best practice untuk menambahkan timestamps
        });

        Schema::create('kategori_pengeluaran', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama_pengeluaran');
            $table->timestamps(); // Best practice untuk menambahkan timestamps
        });

        // Membuat tabel utama
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->date('tanggal');

            // FIX: Mengganti nama kolom foreign key agar sesuai konvensi & memperbaiki typo nama tabel
            $table->foreignUlid('sumber_pemasukan_id')->constrained('sumber_pemasukan')->name('fk_pemasukan_sumber');

            $table->decimal('nominal', 15, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('bukti')->nullable();

            $table->foreignUlid('user_id')->constrained('users')->name('fk_pemasukan_user');
            $table->foreignUlid('rekening_id')->constrained('rekening')->name('fk_pemasukan_rekening');

            // FIX: Menghapus ->after(). Urutan kolom sudah benar.
            $table->ulid('masuk_id')->nullable();
            $table->string('masuk_type')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['masuk_id', 'masuk_type']);
        });

        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->date('tanggal');

            // FIX: Mengganti nama kolom foreign key agar sesuai konvensi
            $table->foreignUlid('kategori_pengeluaran_id')->constrained('kategori_pengeluaran')->name('fk_pengeluaran_kategori');

            $table->decimal('nominal', 15, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('bukti')->nullable();

            // FIX: Mengganti nama foreign key constraint agar unik dan jelas
            $table->foreignUlid('user_id')->constrained('users')->name('fk_pengeluaran_user');
            $table->foreignUlid('rekening_id')->constrained('rekening')->name('fk_pengeluaran_rekening');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan');
        Schema::dropIfExists('sumber_pemasukan');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('kategori_pengeluaran');
    }
};
