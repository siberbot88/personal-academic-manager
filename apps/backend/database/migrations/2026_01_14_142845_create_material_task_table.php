<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_task', function (Blueprint $table) {
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['material_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_task');
    }
};
