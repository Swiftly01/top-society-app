<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_id')->constrained('newsletters')->cascadeOnDelete();
            $table->string('email')->index();

            // Unguessable, unique per subscription (not per email) —
            // each newsletter's unsubscribe link only ever unsubscribes
            // from that one newsletter, never all of them at once.
            $table->string('token', 64)->unique();

            $table->timestamp('subscribed_at');
            $table->timestamp('unsubscribed_at')->nullable()->index();

            $table->timestamps();

            // One subscription row per (email, newsletter) — resubscribing
            // reactivates the same row rather than creating a duplicate.
            $table->unique(['newsletter_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriptions');
    }
};