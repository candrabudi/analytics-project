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
            $table->bigInteger('kol_management_id');
            $table->bigInteger('bank_id');
            $table->bigInteger('account_name');
            $table->bigInteger('account_number');
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
