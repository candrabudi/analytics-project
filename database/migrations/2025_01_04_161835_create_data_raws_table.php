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
        Schema::create('data_raws', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('campaign_budget')->nullable();
            $table->string('amount_spent_idr')->nullable();
            $table->string('adds_of_payment_info')->nullable();
            $table->string('cost_per_add_of_payment_info')->nullable();
            $table->string('leads')->nullable();
            $table->string('cost_per_lead')->nullable();
            $table->string('donations')->nullable();
            $table->string('reach')->nullable();
            $table->string('impressions')->nullable();
            $table->string('cpm')->nullable();
            $table->string('link_clicks')->nullable();
            $table->string('cpc')->nullable();
            $table->string('purchases')->nullable();
            $table->string('cost_per_purchase')->nullable();
            $table->string('adds_to_cart')->nullable();
            $table->string('cost_per_add_to_cart')->nullable();
            $table->string('reporting_starts')->nullable();
            $table->string('reporting_ends')->nullable();
            $table->date('upload_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_raws');
    }
};
