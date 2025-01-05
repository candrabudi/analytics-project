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
        Schema::create('tiktok_searches', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->integer('results')->nullable();
            $table->integer('requests_handled')->nullable();
            $table->integer('requests_total')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->enum('status', ['process', 'abort', 'success'])->default('process');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_searches');
    }
};
