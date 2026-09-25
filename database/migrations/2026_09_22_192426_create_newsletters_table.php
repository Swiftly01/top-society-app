<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            // Daily | Weekly | Weekend — free text, matches the existing
            // small badge shown on each plan card.
            $table->string('badge');
            $table->text('description');

            // Fires an instant email to this newsletter's subscribers
            // whenever any article is published — off by default so
            // creating a newsletter never silently starts spamming
            // people. Deliberately per-newsletter, not global: "Daily
            // Briefing" might want this on, "Weekend Review" almost
            // certainly shouldn't.
            $table->boolean('notify_on_article_publish')->default(false);

            // Which newsletter a subscribe form defaults to when it
            // doesn't specify one (the homepage/footer forms don't ask
            // "which newsletter?" — they just say "Subscribe").
            $table->boolean('is_default')->default(false);

            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletters');
    }
};