<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->longText('body');
            $table->enum('message_type', ['direct', 'broadcast'])->default('direct');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('deleted_by_sender')->default(false);
            $table->boolean('deleted_by_recipient')->default(false);
            $table->timestamps();

            // Index for faster queries
            $table->index('sender_id');
            $table->index('recipient_id');
            $table->index('message_type');
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
