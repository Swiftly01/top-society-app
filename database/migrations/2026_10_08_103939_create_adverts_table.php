<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->id();
            // Internal name + the image's alt text / accessible label.
            $table->string('title');
            $table->string('target_url', 2048);
            $table->boolean('is_active')->default(true);
            // Optional run window — both null means "always on while active".
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Serves the homepage query: active adverts in display order,
            // filtered by the run window.
            $table->index(['is_active', 'display_order']);
            $table->index(['starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adverts');
    }
};
