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
        // 1. Phase Templates (e.g., Riset, Draft, Struktur)
        Schema::create('phase_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Task Type Templates (e.g., Laporan Akhir, Makalah)
        Schema::create('task_type_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('big_threshold_days')->default(14);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Task Type Template Phases (Pivot Table)
        Schema::create('task_type_template_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_type_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('phase_template_id')->constrained()->cascadeOnDelete();
            $table->integer('weight_percent'); // 0-100
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['task_type_template_id', 'phase_template_id'], 'tt_tp_unique');
        });

        // 4. Checklist Templates
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_type_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('phase_template_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_templates');
        Schema::dropIfExists('task_type_template_phases');
        Schema::dropIfExists('task_type_templates');
        Schema::dropIfExists('phase_templates');
    }
};
