<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('name')->comment('Nomor Induk Kependudukan');
        });
        
        // Update existing users with temporary NIK values
        DB::statement("UPDATE users SET nik = CONCAT('temp', LPAD(id, 12, '0')) WHERE nik IS NULL OR nik = ''");
        
        // Now make NIK unique
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nik');
        });
    }
};
