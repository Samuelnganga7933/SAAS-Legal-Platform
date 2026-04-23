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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('email')->encrypted();
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->string('payment_type'); // consultation, service, etc.
            $table->unsignedBigInteger('payable_id')->nullable();
            $table->string('payable_type')->nullable();
            $table->enum('status', ['draft', 'sent', 'viewed', 'paid', 'expired', 'canceled'])->default('draft');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->text('metadata')->nullable()->json();
            $table->string('payment_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index('expires_at');
            $table->index('user_id');
            $table->index(['payable_id', 'payable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
