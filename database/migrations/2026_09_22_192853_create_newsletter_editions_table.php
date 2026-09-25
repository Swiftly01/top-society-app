<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_editions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_id')->constrained('newsletters')->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body');

            // draft | sent — see App\Enums\NewsletterEditionStatus. No
            // "scheduled" state: unlike articles, sending an edition is a
            // deliberate one-way action (you can't "unsend" an email), so
            // there's no scheduling workflow to model here — just
            // draft-until-someone-clicks-Send.
            $table->string('status')->default('draft')->index();
            $table->timestamp('sent_at')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_editions');
    }
};