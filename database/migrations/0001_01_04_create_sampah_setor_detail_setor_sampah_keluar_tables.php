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
            $table->integer('poin_per_kg');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('setor_sampah', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('rekening_id')
                ->constrained('rekening')
                ->index()
                ->name('fk_setor_sampah_rekening');
            $table->foreignUlid('sampah_id')->constrained('sampah')->name('fk_setor_sampah_sampah');
            $table->integer('berat')->required();
            $table->string('calculation_performed');
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_setor_sampah_user');
            $table->decimal('total_saldo_dihasilkan', 15, 2)->default(0);
            $table->bigInteger('total_poin_dihasilkan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('detail_setor_sampah', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUlid('setor_sampah_id')
                ->constrained('setor_sampah')
                ->index()
                ->name('fk_detail_setor_sampah_setor_sampah');
            $table->foreignUlid('sampah_id')
                ->constrained('sampah')
                ->index()
                ->name('fk_detail_setor_sampah_sampah');
            $table->decimal('berat', 8, 2);
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_detail_setor_sampah_user');
            $table->softDeletes();
        });

        Schema::create('sampah_keluar', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('sampah_id')
                ->constrained('sampah')
                ->index()
                ->name('fk_sampah_keluar_sampah');
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_sampah_keluar_user');
            $table->decimal('berat_keluar', 15, 2);
            $table->date('tanggal_keluar')->index();
            $table->text('keterangan')->nullable();
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
