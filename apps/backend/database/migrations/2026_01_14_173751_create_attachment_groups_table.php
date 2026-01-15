<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attachment_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Optional human readable name
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachment_groups');
    }
};
