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
        Schema::create('kol_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_tiktok_account_id');
            $table->unsignedBigInteger('kol_management_id');
            $table->unsignedBigInteger('shipping_provider_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('shipment_number');
            $table->string('receiver_name');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('regency_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('village_id');
            $table->text('destination_address');
            $table->date('shipment_date');
            $table->enum('status', ['pending', 'shipped', 'delivered', 'returned'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kol_shipments');
    }
};
