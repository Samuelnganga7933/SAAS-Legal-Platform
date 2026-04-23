<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'invited', 'inactive'])->default('active')->after('role');
            }
            if (!Schema::hasColumn('users', 'invitation_token')) {
                $table->string('invitation_token')->nullable()->unique()->after('status');
            }
            if (!Schema::hasColumn('users', 'invitation_sent_at')) {
                $table->timestamp('invitation_sent_at')->nullable()->after('invitation_token');
            }
            if (!Schema::hasColumn('users', 'invited_by')) {
                $table->foreignId('invited_by')->nullable()->constrained('users')->onDelete('set null')->after('invitation_sent_at');
            }
            if (!Schema::hasColumn('users', 'deactivated_at')) {
                $table->timestamp('deactivated_at')->nullable()->after('invited_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'invitation_token', 'invitation_sent_at', 'invited_by', 'deactivated_at']);
        });
    }
};
