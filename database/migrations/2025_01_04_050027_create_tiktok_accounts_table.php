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
        Schema::create('tiktok_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tiktok_search_id');
            $table->string('author_id')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('nickname')->nullable();
            $table->string('follower')->nullable();
            $table->string('following')->nullable();
            $table->string('likes')->nullable();
            $table->string('total_video')->nullable();
            $table->string('average')->nullable();
            $table->string('avatar')->nullable();
            $table->string('whatsapp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_accounts');
    }
};
