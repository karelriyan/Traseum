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
        Schema::table('sampah_keluar', function (Blueprint $table) {
            $table->foreignUlid('rekening_id')
                ->constrained('rekening')
                ->name('fk_sampah_keluar_rekening')
                ->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sampah_keluar', function (Blueprint $table) {
            $table->dropForeign('fk_sampah_keluar_rekening');
            $table->dropColumn('rekening_id');
        });
    }
};
