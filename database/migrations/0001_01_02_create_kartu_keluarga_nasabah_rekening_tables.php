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
            $table->string('no_kk', 16)->unique()->index();
            $table->string('nik', 50)->unique()->index();
            $table->string('nama', 255);
            $table->string('gender');
            $table->string('telepon')->nullable();

            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_rekening_user');
            $table->decimal('balance', 15, 2)->default(0);
            $table->bigInteger('points_balance')->default(0);
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
