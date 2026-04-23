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
        Schema::create('client_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('assigned_worker_id')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->enum('case_category', ['money_claim', 'contract', 'compliance', 'general'])->nullable();
            $table->decimal('hourly_rate_override', 8, 2)->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_company');
    }
};
