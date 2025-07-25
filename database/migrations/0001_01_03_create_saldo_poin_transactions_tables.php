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
        Schema::create('saldo_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('rekening_id')
                ->constrained('rekening')
                ->index()
                ->name('fk_saldo_transactions_rekening');
            $table->decimal('amount', 15, 2);
            $table->string('transactable_type', 255)->index();
            $table->ulid('transactable_id')->index();
            $table->string('description', 255);
            $table->timestamps();
        });

        Schema::create('poin_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('rekening_id')
                ->constrained('rekening')
                ->index()
                ->name('fk_poin_transactions_rekening');
            $table->bigInteger('amount');
            $table->string('transactable_type', 255)->index();
            $table->ulid('transactable_id')->index();
            $table->string('description', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_transactions');
        Schema::dropIfExists('saldo_transactions');
    }
};
