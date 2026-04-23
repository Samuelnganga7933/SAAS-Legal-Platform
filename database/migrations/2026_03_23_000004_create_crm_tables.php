<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->enum('source', ['direct', 'referral', 'ads', 'website', 'other'])->default('direct');
            $table->enum('status', ['lead', 'prospect', 'client', 'inactive'])->default('lead');
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('source');
            $table->index('assigned_to');
        });

        Schema::create('crm_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('crm_contacts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['call', 'email', 'meeting', 'note']);
            $table->text('summary');
            $table->dateTime('interaction_date');
            $table->timestamps();
            
            $table->index('contact_id');
            $table->index('interaction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_interactions');
        Schema::dropIfExists('crm_contacts');
    }
};
