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
        Schema::create('raw_tiktok_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('author_id')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('nickname')->nullable();
            $table->integer('follower')->default(0);
            $table->integer('following')->default(0);
            $table->integer('like')->default(0);
            $table->integer('total_video')->default(0);
            $table->integer('avg_views')->default(0);
            $table->double('engagement_rate')->default(0);
            $table->enum('status_call', ['pending', 'response', 'no_response'])->default('pending');
            $table->string('whatsapp_number')->default(0)->nullable();
            $table->string('notes')->default(0)->nullable();
            $table->string('file')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_tiktok_accounts');
    }
};
