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
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedSmallInteger('health_score')->default(100);
            $table->string('health_status')->default('aman')->index();
            $table->dateTime('last_progress_at')->nullable()->index();
            $table->boolean('attention_flag')->default(false)->index();
            $table->boolean('priority_boost')->default(false);
            $table->unsignedTinyInteger('stagnation_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'health_score',
                'health_status',
                'last_progress_at',
                'attention_flag',
                'priority_boost',
                'stagnation_days',
            ]);
        });
    }
};
