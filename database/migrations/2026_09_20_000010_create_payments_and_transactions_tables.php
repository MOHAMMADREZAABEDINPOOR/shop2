<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gateway')->default('test_gateway');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('reference_id')->nullable()->index();
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('IRR');
            $table->string('status')->default('pending')->index(); // pending, successful, failed, refunded
            $table->string('idempotency_key')->unique();
            $table->json('payload')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // request, callback, verify, webhook
            $table->string('status'); // success, failure
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();

            $table->index(['payment_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('payments');
    }
};
