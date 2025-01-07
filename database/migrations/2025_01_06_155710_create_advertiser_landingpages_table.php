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
        Schema::create('advertiser_landingpages', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('link');
            $table->enum('landingpage_status', ['active', 'inactive']);
            $table->enum('cta_status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertiser_landingpages');
    }
};
