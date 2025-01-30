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
        Schema::create('tiktok_progress_posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kol_management_id');
            $table->string('link_post')->nullable();
            $table->date('deadline');
            $table->date('date_post');
            $table->bigInteger('views');
            $table->bigInteger('likes');
            $table->bigInteger('comments');
            $table->bigInteger('shares');
            $table->bigInteger('saves');
            $table->string('brief')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_progress_posts');
    }
};
