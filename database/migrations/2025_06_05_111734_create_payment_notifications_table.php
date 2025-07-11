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
        Schema::create('payment_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->index();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->decimal('gross_amount', 15, 2)->nullable();
            $table->string('currency')->nullable();
            $table->string('fraud_status')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->json('raw_payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_notifications');
    }
};
