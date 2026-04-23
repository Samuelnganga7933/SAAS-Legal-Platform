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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'client', 'worker', 'team_member'])->default('client')->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('role')->constrained('companies')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'subscription_tier')) {
                $table->enum('subscription_tier', ['starter', 'professional', 'enterprise'])->nullable()->after('company_id');
            }
            if (!Schema::hasColumn('users', 'subscription_start')) {
                $table->timestamp('subscription_start')->nullable()->after('subscription_tier');
            }
            if (!Schema::hasColumn('users', 'subscription_end')) {
                $table->timestamp('subscription_end')->nullable()->after('subscription_start');
            }
            if (!Schema::hasColumn('users', 'is_subscription_active')) {
                $table->boolean('is_subscription_active')->default(false)->after('subscription_end');
            }
            if (!Schema::hasColumn('users', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->after('is_subscription_active');
            }
            if (!Schema::hasColumn('users', 'hourly_rate')) {
                $table->decimal('hourly_rate', 8, 2)->nullable()->after('stripe_customer_id');
            }
            if (!Schema::hasColumn('users', 'availability_hours')) {
                $table->text('availability_hours')->nullable()->after('hourly_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'company_id',
                'subscription_tier',
                'subscription_start',
                'subscription_end',
                'is_subscription_active',
                'stripe_customer_id',
                'hourly_rate',
                'availability_hours',
            ]);
        });
    }
};
