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
            $table->dateTime('first_touched_at')->nullable()->after('status');
            $table->integer('started_lead_days')->nullable()->after('first_touched_at')
                ->comment('Difference in days between due_date and first_touched_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['first_touched_at', 'started_lead_days']);
        });
    }
};
