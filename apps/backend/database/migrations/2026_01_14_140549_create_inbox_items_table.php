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
        Schema::create('inbox_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->string('type')->default('link');
            $table->string('title');
            $table->text('url');
            $table->text('note')->nullable();
            $table->timestamp('captured_at')->useCurrent();
            $table->string('source')->nullable(); // WA, LMS, Drive, Other
            $table->timestamps();

            // Indexes for performance
            $table->index(['course_id', 'captured_at']);
            $table->index('task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox_items');
    }
};
