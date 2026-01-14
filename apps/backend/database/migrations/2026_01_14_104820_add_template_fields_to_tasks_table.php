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
            $table->foreignId('task_type_template_id')
                ->nullable()
                ->after('title')
                ->constrained('task_type_templates')
                ->nullOnDelete();

            $table->string('size')
                ->default('auto')
                ->after('task_type_template_id')
                ->comment('auto, small, big');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['task_type_template_id']);
            $table->dropColumn(['task_type_template_id', 'size']);
        });
    }
};
