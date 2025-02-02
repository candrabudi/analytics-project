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
            $table->bigInteger('raw_tiktok_account_id');
            $table->bigInteger('kol_management_id');
            $table->string('title');
            $table->string('link_post')->nullable();
            $table->date('deadline');
            $table->date('date_post')->nullable();
            $table->bigInteger('target_views');
            $table->bigInteger('views')->default(0);
            $table->bigInteger('likes')->default(0);
            $table->bigInteger('comments')->default(0);
            $table->bigInteger('shares')->default(0);
            $table->bigInteger('saves')->default(0);
            $table->text('brief')->nullable();
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
