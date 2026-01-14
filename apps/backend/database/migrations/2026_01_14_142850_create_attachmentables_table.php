<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attachmentables', function (Blueprint $table) {
            $table->foreignId('attachment_id')->constrained('attachments')->cascadeOnDelete();
            $table->string('attachmentable_type');
            $table->unsignedBigInteger('attachmentable_id');
            $table->string('role')->nullable(); // primary|supporting
            $table->timestamp('created_at')->useCurrent();

            // Index for polymorphic relation
            $table->index(['attachmentable_type', 'attachmentable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachmentables');
    }
};
