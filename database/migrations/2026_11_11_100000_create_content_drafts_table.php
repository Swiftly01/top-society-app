<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Scopes a draft to one form: "<resource class>:create" or "<resource class>:<record id>".
            $table->string('draft_key', 150);
            $table->json('payload');
            // Hash of the payload, lets us skip writes when nothing changed.
            $table->string('checksum', 40);
            $table->timestamps();

            $table->unique(['user_id', 'draft_key']);
            $table->index('updated_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_drafts');
    }
};
