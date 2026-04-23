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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('case_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['open', 'in-progress', 'closed', 'withdrawn'])->default('open');
            $table->string('case_type')->nullable(); // e.g., 'legal', 'consultation', 'support'
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->dateTime('filed_date')->useCurrent();
            $table->dateTime('closed_date')->nullable();
            $table->dateTime('withdrawn_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Store additional data
            $table->timestamps();
            $table->softDeletes();

            // Indexes for faster queries
            $table->index('user_id');
            $table->index('status');
            $table->index('priority');
            $table->index('case_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
