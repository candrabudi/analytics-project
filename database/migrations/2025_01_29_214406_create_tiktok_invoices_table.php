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
        Schema::create('tiktok_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_tiktok_account_id');
            $table->unsignedBigInteger('kol_management_id');
            $table->unsignedBigInteger('bank_id');
            $table->bigInteger('account_name');
            $table->bigInteger('account_number');
            $table->string('file_upload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiktok_invoices');
    }
};
