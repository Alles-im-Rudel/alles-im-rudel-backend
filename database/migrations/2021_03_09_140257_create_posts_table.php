<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('color');

            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->longText('text');

            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('tag_id')->references('id')->on('tags')->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->morphs('commentable');
            $table->text('text');

            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });

        Schema::create('model_tag', function (Blueprint $table) {
            $table->id();

            $table->morphs('tagable');
            $table->foreignId('tag_id')->references('id')->on('tags')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('model_tag');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('tags');
    }
}
