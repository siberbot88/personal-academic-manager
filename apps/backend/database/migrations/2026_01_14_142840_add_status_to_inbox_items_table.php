<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inbox_items', function (Blueprint $table) {
            $table->string('status')->default('inbox')->after('source'); // inbox|promoted|archived
            $table->foreignId('promoted_to_material_id')->nullable()->constrained('materials')->nullOnDelete()->after('status');
            $table->timestamp('processed_at')->nullable()->after('promoted_to_material_id');

            $table->index(['status', 'captured_at']);
        });
    }

    public function down(): void
    {
        Schema::table('inbox_items', function (Blueprint $table) {
            $table->dropIndex(['status', 'captured_at']);
            $table->dropForeign(['promoted_to_material_id']);
            $table->dropColumn(['status', 'promoted_to_material_id', 'processed_at']);
        });
    }
};
