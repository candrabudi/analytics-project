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
            $table->enum('tier', ['nano', 'micro', 'macro', 'mega'])->default('nano');
            $table->integer('is_call')->default(0);
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('village')->nullable();
            $table->text('address')->nullable();
            $table->integer('total_sales')->default(0);
            $table->integer('total_views')->default(0);
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
