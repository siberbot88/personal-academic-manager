<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->foreignId('attachment_group_id')->nullable()->constrained('attachment_groups')->nullOnDelete();
            $table->integer('version_number')->default(1);
            $table->boolean('is_current')->default(true);
            $table->boolean('is_final')->default(false);

            // Index for fast version lookup
            $table->index(['attachment_group_id', 'version_number']);
            $table->index(['attachment_group_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign(['attachment_group_id']);
            $table->dropColumn(['attachment_group_id', 'version_number', 'is_current', 'is_final']);
        });
    }
};
