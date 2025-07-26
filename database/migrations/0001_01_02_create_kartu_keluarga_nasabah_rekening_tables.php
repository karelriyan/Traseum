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
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nomor_kk', 50)->unique()->index();
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_kartu_keluarga_user');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nasabah', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('kartu_keluarga_id')->constrained('kartu_keluarga')->onDelete('cascade')->index()->name('fk_nasabah_kartu_keluarga');
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_nasabah_user');
            $table->string('nama', 255);
            $table->string('nik', 50)->unique()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rekening', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('kartu_keluarga_id')
                ->constrained('kartu_keluarga')
                ->onDelete('cascade')
                ->unique()
                ->index()
                ->name('fk_rekening_kartu_keluarga');
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_rekening_user');
            $table->decimal('balance', 15, 2);
            $table->bigInteger('points_balance');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening');
        Schema::dropIfExists('nasabah');
        Schema::dropIfExists('kartu_keluarga');
    }
};
