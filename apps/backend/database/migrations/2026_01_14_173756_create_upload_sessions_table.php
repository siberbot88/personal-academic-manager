<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('upload_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_email')->nullable()->index();
            $table->string('attachable_type');
            $table->unsignedBigInteger('attachable_id');
            $table->unsignedBigInteger('attachment_group_id')->nullable();

            $table->text('object_key'); // R2 Key
            $table->string('mime_type');
            $table->unsignedBigInteger('size_bytes');
            $table->string('original_name');

            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            $table->index(['attachable_type', 'attachable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_sessions');
    }
};
