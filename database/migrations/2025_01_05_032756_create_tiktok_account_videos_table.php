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
        Schema::create('tiktok_account_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tiktok_account_id');
            $table->string('aweme_id')->unique();
            $table->string('video_id')->unique();
            $table->string('region');
            $table->string('title');
            $table->string('cover');
            $table->integer('duration');
            $table->string('play');
            $table->integer('play_count');
            $table->integer('digg_count');
            $table->integer('comment_count');
            $table->integer('share_count');
            $table->integer('download_count');
            $table->integer('collect_count');
            $table->timestamp('create_time');
            $table->boolean('is_top')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_account_videos');
    }
};
