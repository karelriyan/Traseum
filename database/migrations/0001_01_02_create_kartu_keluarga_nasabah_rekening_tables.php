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
            $table->ulid('id')->primary();
            $table->string('no_rekening', 8)->unique()->index();

            $table->string('nama', 255);
            $table->string('dusun', 3);
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('gender');
            $table->string('no_kk', 16)->unique()->index();
            $table->string('nik', 16)->unique()->index();
            $table->date('tanggal_lahir');
            $table->string('pendidikan');

            $table->string('telepon')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->bigInteger('points_balance')->default(100);

            $table->boolean('status_pegadaian')->default(false);
            $table->string('no_rek_pegadaian', 16)->unique()->nullable();

            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_rekening_user');
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
    }
};
