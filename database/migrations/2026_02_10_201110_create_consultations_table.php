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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('email')->encrypted();
            $table->string('full_name')->encrypted();
            $table->string('phone')->nullable()->encrypted();
            $table->dateTime('preferred_date_time')->nullable();
            $table->text('description')->nullable()->encrypted();
            $table->enum('status', ['pending', 'scheduled', 'completed', 'cancelled'])->default('pending');
            $table->string('zoom_link')->nullable()->encrypted();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index('preferred_date_time');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
