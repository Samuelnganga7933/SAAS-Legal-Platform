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
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('roles')
                    ->nullOnDelete();
            }
            
            if (!Schema::hasColumn('users', 'is_ceo')) {
                $table->boolean('is_ceo')->default(false)->after('is_admin');
            }
            
            if (!Schema::hasColumn('users', 'type')) {
                $table->enum('type', ['client', 'worker'])
                    ->default('client')
                    ->after('role_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeignKeyIfExists(['role_id']);
                $table->dropColumn('role_id');
            }
            
            if (Schema::hasColumn('users', 'is_ceo')) {
                $table->dropColumn('is_ceo');
            }
            
            if (Schema::hasColumn('users', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
