<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add billing columns if they don't exist
            if (!Schema::hasColumn('users', 'subscription_plan')) {
                $table->string('subscription_plan')->nullable()->after('subscription_tier');
            }
            if (!Schema::hasColumn('users', 'monthly_rate')) {
                $table->decimal('monthly_rate', 8, 2)->nullable()->after('subscription_plan');
            }
            if (!Schema::hasColumn('users', 'subscription_start_date')) {
                $table->timestamp('subscription_start_date')->nullable()->after('subscription_start');
            }
            if (!Schema::hasColumn('users', 'next_billing_date')) {
                $table->timestamp('next_billing_date')->nullable()->after('subscription_start_date');
            }
            if (!Schema::hasColumn('users', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('next_billing_date');
            }
            if (!Schema::hasColumn('users', 'is_subscribed')) {
                $table->boolean('is_subscribed')->default(false)->after('cancelled_at');
            }
            if (!Schema::hasColumn('users', 'stripe_card_last_four')) {
                $table->string('stripe_card_last_four')->nullable()->after('stripe_customer_id');
            }
            if (!Schema::hasColumn('users', 'stripe_card_brand')) {
                $table->string('stripe_card_brand')->nullable()->after('stripe_card_last_four');
            }
            if (!Schema::hasColumn('users', 'stripe_card_expiry')) {
                $table->string('stripe_card_expiry')->nullable()->after('stripe_card_brand');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_plan',
                'monthly_rate',
                'subscription_start_date',
                'next_billing_date',
                'cancelled_at',
                'is_subscribed',
                'stripe_card_last_four',
                'stripe_card_brand',
                'stripe_card_expiry',
            ]);
        });
    }
};
