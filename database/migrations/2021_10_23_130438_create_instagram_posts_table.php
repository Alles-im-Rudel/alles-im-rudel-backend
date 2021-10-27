<?php

use App\Models\InstagramPost;
use App\Models\InstagramProfile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstagramPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('instagram_id');

            $table->string('name');
            $table->string('display_name')->nullable();
            $table->text('image_url')->nullable();

            $table->text('description')->nullable();
            $table->text('description_url')->nullable();

            $table->timestamps();
        });

        Schema::create('instagram_posts', function (Blueprint $table) {
            $table->id();
            $table->string('instagram_id');

            $table->foreignIdFor(InstagramProfile::class)
                ->references('id')
                ->on('instagram_profiles')
                ->cascadeOnDelete();

            $table->text('description')->nullable();

            $table->dateTime('notified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('instagram_images', function (Blueprint $table) {
            $table->id();
            $table->string('instagram_id');

            $table->foreignIdFor(InstagramPost::class)
                ->references('id')
                ->on('instagram_posts')
                ->cascadeOnDelete();

            $table->text('image_url');

            $table->timestamps();
        });

        Schema::create('instagram_videos', function (Blueprint $table) {
            $table->id();
            $table->string('instagram_id');

            $table->foreignIdFor(InstagramPost::class)
                ->references('id')
                ->on('instagram_posts')
                ->cascadeOnDelete();

            $table->text('thumbnail_url');
            $table->text('video_url');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instagram_videos');
        Schema::dropIfExists('instagram_images');
        Schema::dropIfExists('instagram_posts');
        Schema::dropIfExists('instagram_profiles');
    }
}
