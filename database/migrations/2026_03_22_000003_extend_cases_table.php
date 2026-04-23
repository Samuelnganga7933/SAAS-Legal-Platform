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
        Schema::table('cases', function (Blueprint $table) {
            $table->foreignId('assigned_worker_id')->nullable()->after('id')->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('company_id')->nullable()->after('assigned_worker_id')->constrained('companies')->onDelete('set null');
            $table->enum('case_category', ['money_claim', 'contract', 'compliance', 'general'])->default('general')->after('company_id');
            $table->json('status_timeline')->nullable()->after('case_category');
            $table->boolean('hourly_billable')->default(false)->after('status_timeline');
            $table->decimal('fixed_fee', 10, 2)->nullable()->after('hourly_billable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropForeignIdFor('assigned_worker_id');
            $table->dropForeignIdFor('company_id');
            $table->dropColumn([
                'assigned_worker_id',
                'company_id',
                'case_category',
                'status_timeline',
                'hourly_billable',
                'fixed_fee',
            ]);
        });
    }
};
