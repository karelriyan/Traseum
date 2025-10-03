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
        // Drop existing pivot with incorrect column type for user_id
        Schema::dropIfExists('hexa_role_user');

        // Recreate pivot with ULID user_id to match users table primary key
        Schema::create('hexa_role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('hexa_roles')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hexa_role_user');

        // Recreate original vendor structure (integer user_id) if needed.
        Schema::create('hexa_role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('hexa_roles');
            $table->foreignId('user_id');
        });
    }
};