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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('primary_course_id')->constrained('courses')->cascadeOnDelete();
            $table->date('due_date')->nullable();
            $table->enum('status', ['Active', 'Done', 'Archived'])->default('Active');
            $table->integer('progress')->default(0);
            $table->foreignId('type_template_id')->nullable(); // For Week 3+
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
