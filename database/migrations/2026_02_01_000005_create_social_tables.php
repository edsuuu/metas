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
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->string('type')->default('generic');
            $table->json('metadata')->nullable();
            $table->boolean('is_edited')->default(false);
            $table->text('original_content')->nullable();
            $table->string('original_file_uuid')->nullable();
            $table->timestamps();
        });

        Schema::create('social_post_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('social_post_id')->constrained('social_posts')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'social_post_id']);
        });

        Schema::create('social_post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('social_post_id')->constrained('social_posts')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Sender
            $table->foreignId('friend_id')->constrained('users')->cascadeOnDelete(); // Receiver
            $table->enum('status', ['pending', 'accepted', 'blocked'])->default('pending');
            $table->timestamps();
            $table->unique(['user_id', 'friend_id']);
        });

         Schema::create('social_post_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained('social_posts')->cascadeOnDelete();
            $table->string('reason');
            $table->text('details')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('social_post_hides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained('social_posts')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_post_hides');
        Schema::dropIfExists('social_post_reports');
        Schema::dropIfExists('friendships');
        Schema::dropIfExists('social_post_comments');
        Schema::dropIfExists('social_post_likes');
        Schema::dropIfExists('social_posts');
    }
};
