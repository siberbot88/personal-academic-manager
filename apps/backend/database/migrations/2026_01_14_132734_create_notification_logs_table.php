<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // daily_digest, pre_study, stagnation_alert, bahaya_alert
            $table->string('channel')->default('email');
            $table->string('to_email');
            $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();

            // Compound indexes for fast lookup (throttling)
            $table->index(['type', 'to_email', 'sent_at']);
            $table->index(['type', 'task_id', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
