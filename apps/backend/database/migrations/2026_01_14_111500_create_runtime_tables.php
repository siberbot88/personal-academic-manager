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
        // 1. Task Phases (Runtime instances of phases)
        Schema::create('task_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('phase_template_id')->nullable()->constrained()->nullOnDelete(); // Nullable if custom phase
            $table->string('title'); // Copied from template or custom
            $table->integer('sort_order')->default(0);
            $table->date('due_date')->nullable();
            $table->date('start_date')->nullable(); // Optional
            $table->string('status')->default('todo'); // todo, doing, done
            $table->timestamps();

            // Prevent duplicate phases from same template per task? Maybe not strict unique if re-added?
            // strict unique for now to prevent double generation
            $table->unique(['task_id', 'phase_template_id']);
        });

        // 2. Checklist Items (Runtime instances)
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_phase_id')->constrained('task_phases')->cascadeOnDelete();
            $table->string('title');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_done')->default(false);
            $table->timestamp('done_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
        Schema::dropIfExists('task_phases');
    }
};
