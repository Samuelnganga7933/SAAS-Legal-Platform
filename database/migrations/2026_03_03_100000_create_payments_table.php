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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('stripe_payment_intent_id')->nullable()->unique();
            $table->string('payment_type'); // 'consultation', 'service', etc.
            $table->unsignedBigInteger('payable_id')->nullable();
            $table->string('payable_type')->nullable(); // Polymorphic relation
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('customer_email')->encrypted();
            $table->string('customer_name')->encrypted();
            $table->decimal('amount', 15, 2); // Amount in cents will be converted
            $table->string('currency')->default('USD');
            $table->enum('status', ['pending', 'processing', 'succeeded', 'failed', 'refunded', 'canceled'])->default('pending');
            $table->string('payment_method')->nullable(); // 'card', 'bank_transfer', etc.
            $table->text('stripe_response')->nullable()->comment('JSON response from Stripe');
            $table->string('failure_message')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->decimal('refunded_amount', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('metadata')->nullable()->comment('Additional JSON metadata');
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('stripe_payment_intent_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
            $table->index(['payable_id', 'payable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
