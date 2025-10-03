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
        // Recreate the pivot table to ensure user_id uses ULID to match users table primary key
        Schema::dropIfExists('hexa_role_user');

        Schema::create('hexa_role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('hexa_roles')->cascadeOnDelete();
            $table->ulid('user_id');
            // Optional: prevent duplicate entries
            $table->primary(['role_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hexa_role_user');

        Schema::create('hexa_role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('hexa_roles');
            $table->foreignId('user_id');
        });
    }
};