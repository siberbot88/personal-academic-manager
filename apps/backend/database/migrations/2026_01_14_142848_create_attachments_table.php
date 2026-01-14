<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('storage_driver')->default('local_private'); // future: r2
            $table->text('storage_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('checksum_sha256')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->string('label')->nullable(); // v1/v2/final
            $table->text('note')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('storage_driver');
            $table->index('checksum_sha256');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
