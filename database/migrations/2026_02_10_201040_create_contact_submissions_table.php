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
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->encrypted();
            $table->string('email')->encrypted();
            $table->string('country')->nullable()->encrypted();
            $table->string('service')->nullable()->encrypted();
            $table->longText('message')->encrypted();
            $table->boolean('consent')->default(false);
            $table->enum('status', ['pending', 'reviewed', 'responded'])->default('pending');
            $table->string('ip_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('email');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
