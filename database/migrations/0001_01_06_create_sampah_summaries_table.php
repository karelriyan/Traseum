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
        Schema::create('sampah_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignUlid('sampah_id')
                ->constrained('sampah')
                ->index()
                ->name('fk_sampah_summaries_sampah');
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index();
            $table->date('tanggal_summary')->index();
            $table->decimal('total_berat_masuk', 15, 2);
            $table->decimal('total_berat_keluar', 15, 2);
            $table->unique(['sampah_id', 'tanggal_summary']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampah_summaries');
    }
};
