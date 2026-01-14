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
        Schema::table('task_phases', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress_pct')->default(0)->after('status');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress_pct')->default(0)->after('status');
        });

        // Add index for faster checklist aggregation
        Schema::table('checklist_items', function (Blueprint $table) {
            $table->index(['task_phase_id', 'is_done']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('checklist_items', function (Blueprint $table) {
            $table->dropIndex(['task_phase_id', 'is_done']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('progress_pct');
        });

        Schema::table('task_phases', function (Blueprint $table) {
            $table->dropColumn('progress_pct');
        });
    }
};
