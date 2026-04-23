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
        Schema::table('messages', function (Blueprint $table) {
            // Add delivery status tracking (if not already exists)
            if (!Schema::hasColumn('messages', 'delivery_status')) {
                $table->enum('delivery_status', ['pending', 'delivered', 'failed'])->default('pending')->after('message_type');
            }
            if (!Schema::hasColumn('messages', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('delivery_status');
            }
            if (!Schema::hasColumn('messages', 'delivery_error')) {
                $table->text('delivery_error')->nullable()->after('delivered_at');
            }
            
            // Add case reference for case-related messages
            if (!Schema::hasColumn('messages', 'case_id')) {
                $table->foreignId('case_id')->nullable()->constrained('cases')->onDelete('set null')->after('recipient_id');
            }
            
            // Add encryption flag
            if (!Schema::hasColumn('messages', 'is_encrypted')) {
                $table->boolean('is_encrypted')->default(true)->after('delivery_error');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'delivery_status')) {
                $table->dropColumn('delivery_status');
            }
            if (Schema::hasColumn('messages', 'delivered_at')) {
                $table->dropColumn('delivered_at');
            }
            if (Schema::hasColumn('messages', 'delivery_error')) {
                $table->dropColumn('delivery_error');
            }
            if (Schema::hasColumn('messages', 'case_id')) {
                $table->dropForeign(['case_id']);
                $table->dropColumn('case_id');
            }
            if (Schema::hasColumn('messages', 'is_encrypted')) {
                $table->dropColumn('is_encrypted');
            }
        });
    }
};
