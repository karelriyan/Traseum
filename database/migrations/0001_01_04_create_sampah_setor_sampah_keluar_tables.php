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
        Schema::create('sampah', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('jenis_sampah', 255)->unique();
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_sampah_user');
            $table->decimal('saldo_per_kg', 15, 2);
            $table->unsignedInteger('poin_per_kg')->nullable();
            $table->decimal('total_berat_terkumpul', 15, 4)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('setor_sampah', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->date('tanggal');
            $table->foreignUlid('rekening_id')
                ->constrained('rekening')
                ->index()
                ->name('fk_setor_sampah_rekening');
            $table->string('jenis_setoran'); // 'rekening' atau 'donasi'
            $table->decimal('berat', 10, 4)->default(0); // Total berat dari semua item
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_setor_sampah_user'); // Dihapus di panduan, tapi ada di form
            $table->decimal('total_saldo_dihasilkan', 15, 2)->default(0);
            $table->bigInteger('total_poin_dihasilkan')->default(0);
            $table->boolean('calculation_performed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sampah_keluar', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('jenis_keluar'); // 'jual' atau 'bakar'
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_sampah_keluar_user');
            $table->decimal('berat_keluar', 15, 4)->default(0);
            $table->date('tanggal_keluar')->index();
            $table->decimal('total_saldo_dihasilkan', 15, 2)->default(0);
            $table->boolean('calculation_performed')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampah_keluar');
        Schema::dropIfExists('detail_setor_sampah');
        Schema::dropIfExists('setor_sampah');
        Schema::dropIfExists('sampah');
    }
};
