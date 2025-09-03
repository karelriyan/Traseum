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
        Schema::table('news', function (Blueprint $table) {
            $table->string('slug')->unique()->after('title');
            $table->text('excerpt')->nullable()->after('slug');
            $table->string('featured_image')->nullable()->after('content');
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft')->after('featured_image');
            $table->datetime('published_at')->nullable()->after('status');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null')->after('published_at');
            $table->string('category')->default('general')->after('author_id');
            $table->json('tags')->nullable()->after('category');
            $table->string('meta_title')->nullable()->after('tags');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->unsignedBigInteger('views_count')->default(0)->after('meta_description');
            $table->softDeletes()->after('updated_at');
            
            // Drop old columns if they exist
            $table->dropColumn(['image', 'author']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'slug', 'excerpt', 'featured_image', 'status', 'published_at', 
                'author_id', 'category', 'tags', 'meta_title', 'meta_description', 'views_count'
            ]);
            
            // Restore old columns
            $table->string('image')->nullable();
            $table->string('author')->nullable();
        });
    }
};
