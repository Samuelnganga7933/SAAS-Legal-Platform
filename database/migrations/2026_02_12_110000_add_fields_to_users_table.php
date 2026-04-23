<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'account_type')) {
                $table->string('account_type')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'is_verified_client')) {
                $table->boolean('is_verified_client')->default(false)->after('account_type');
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('is_verified_client');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'account_type')) {
                $table->dropColumn('account_type');
            }
            if (Schema::hasColumn('users', 'is_verified_client')) {
                $table->dropColumn('is_verified_client');
            }
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });
    }
};
