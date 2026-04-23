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
            // Profile fields
            if (!Schema::hasColumn('users', 'job_title')) {
                $table->string('job_title')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }

            // Notification preferences
            if (!Schema::hasColumn('users', 'notify_case_assigned')) {
                $table->boolean('notify_case_assigned')->default(true);
            }
            if (!Schema::hasColumn('users', 'notify_message')) {
                $table->boolean('notify_message')->default(true);
            }
            if (!Schema::hasColumn('users', 'notify_payment')) {
                $table->boolean('notify_payment')->default(true);
            }
            if (!Schema::hasColumn('users', 'notify_deadline')) {
                $table->boolean('notify_deadline')->default(true);
            }
            if (!Schema::hasColumn('users', 'notify_team_activity')) {
                $table->boolean('notify_team_activity')->default(true);
            }
            if (!Schema::hasColumn('users', 'notify_weekly_digest')) {
                $table->boolean('notify_weekly_digest')->default(true);
            }

            // Appearance
            if (!Schema::hasColumn('users', 'theme')) {
                $table->string('theme')->default('light')->comment('light, dark, auto');
            }

            // Integrations (CEO only)
            if (!Schema::hasColumn('users', 'google_calendar_connected')) {
                $table->boolean('google_calendar_connected')->default(false);
            }
            if (!Schema::hasColumn('users', 'google_ads_connected')) {
                $table->boolean('google_ads_connected')->default(false);
            }
            if (!Schema::hasColumn('users', 'meta_ads_connected')) {
                $table->boolean('meta_ads_connected')->default(false);
            }
            if (!Schema::hasColumn('users', 'stripe_connected')) {
                $table->boolean('stripe_connected')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'job_title', 'phone', 'profile_photo_path',
                'notify_case_assigned', 'notify_message', 'notify_payment',
                'notify_deadline', 'notify_team_activity', 'notify_weekly_digest',
                'theme', 'google_calendar_connected', 'google_ads_connected',
                'meta_ads_connected', 'stripe_connected',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
