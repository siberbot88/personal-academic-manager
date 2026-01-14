<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->string('type'); // note|link|file
            $table->text('url')->nullable();
            $table->text('note')->nullable();
            $table->string('source')->nullable(); // WA/LMS/Drive/Other
            $table->timestamp('captured_at')->useCurrent();
            $table->foreignId('inbox_item_id')->nullable()->constrained('inbox_items')->nullOnDelete();
            $table->timestamps();

            // Indexes
            $table->index(['course_id', 'captured_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
