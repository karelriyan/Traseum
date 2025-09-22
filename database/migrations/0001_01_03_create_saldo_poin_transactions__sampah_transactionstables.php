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
            $table->string('type')->nullable();
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_saldo_transactions_user');
            $table->decimal('amount', 15, 2);
            $table->string('transactable_type', 255)->index();
            $table->ulid('transactable_id')->index();
            $table->string('description', 255);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sampah_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ulidMorphs('transactable');
            $table->foreignUlid('sampah_id')
                ->constrained('sampah')
                ->index()
                ->name('fk_detail_setor_sampah_sampah');
            $table->foreignUlid('rekening_id')
                ->constrained('rekening')
                ->index()
                ->name('fk_detail_setor_sampah_rekening');
            $table->decimal('berat', 10, 4);
            $table->string('description', 255);
            $table->foreignUlid(column: 'user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_detail_setor_sampah_user');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('poin_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('rekening_id')
                ->constrained('rekening')
                ->index()
                ->name('fk_poin_transactions_rekening');
            $table->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete()->index()->name('fk_poin_transactions_rekening_user');
            $table->bigInteger('amount');
            $table->string('transactable_type', 255)->index();
            $table->ulid('transactable_id')->index();
            $table->string('description', 255);
            $table->timestamps();
            $table->softDeletes();
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
