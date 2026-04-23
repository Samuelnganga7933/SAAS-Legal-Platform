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
            $table->string('email_verification_token')->nullable()->unique()->after('email');
            $table->dateTime('email_verification_token_expires_at')->nullable()->after('email_verification_token');
            $table->boolean('email_verified')->default(false)->after('email_verification_token_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verification_token', 'email_verification_token_expires_at', 'email_verified']);
        });
    }
};
