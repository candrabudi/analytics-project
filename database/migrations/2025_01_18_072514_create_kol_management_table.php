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
        Schema::create('kol_management', function (Blueprint $table) {
            $table->id();
            $table->string('kol_trx_no');
            $table->bigInteger('raw_tiktok_account_id');
            $table->bigInteger('pic_id');
            $table->enum('platform', ['Instagram', 'TikTok', 'Facebook', 'SnackVideo', 'Youtube', 'Google'])->default('tiktok');
            $table->integer('ratecard_kol')->nullable();
            $table->integer('ratecard_deal')->nullable();
            $table->integer('target_views')->nullable();
            $table->integer('views_achieved')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->date('deal_date')->nullable();
            $table->integer('deal_post')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kol_management');
    }
};
