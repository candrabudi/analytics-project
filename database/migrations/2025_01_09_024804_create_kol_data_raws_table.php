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
        Schema::create('kol_data_raws', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('link_account');
            $table->string('followers');
            $table->enum('tier', ['nano', 'micro', 'macro', 'mega'])->default('nano');
            $table->string('notes')->nullable();
            $table->string('contact')->nullable();
            $table->string('chat')->nullable();
            $table->string('gmv')->nullable();
            $table->string('er_account')->nullable();
            $table->string('rate_card')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kol_data_raws');
    }
};
