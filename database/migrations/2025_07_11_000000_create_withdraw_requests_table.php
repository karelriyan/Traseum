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
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('rekening_id')
                ->constrained('rekening')
                ->index()
                ->name('fk_withdraw_requests_rekening');
            $table->foreignUlid('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->index()
                ->name('fk_withdraw_requests_user');
            $table->decimal('amount', 15, 2);
            $table->string('jenis');
            $table->text('catatan')->nullable();

            $table->boolean('is_new_pegadaian_registration')->default(false);
            $table->string('no_rek_pegadaian', 16)->nullable();

            $table->foreignUlid('processed_by')->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->index()
                ->name('fk_withdraw_requests_processed_by');
            $table->timestamp('processed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_requests');
    }
};
