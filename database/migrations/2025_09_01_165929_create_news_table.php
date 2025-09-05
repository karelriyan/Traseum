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
        Schema::create('news', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('title');
            $table->longText('content');
            $table->string('image')->nullable();
            $table->string('category')->default('general');

            $table->json('tags')->nullable();

            $table->unsignedBigInteger('views_count')->default(0);

            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade') ;
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('news');
    }
};
