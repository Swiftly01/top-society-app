<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Existing adverts keep working: they land in the original slot.
            $table->string('placement', 32)->default('hero_rail')->after('title');

            // Every slot's query filters by placement first, so the
            // composite index leads with it.
            $table->dropIndex(['is_active', 'display_order']);
            $table->index(['placement', 'is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->dropIndex(['placement', 'is_active', 'display_order']);
            $table->index(['is_active', 'display_order']);
            $table->dropColumn('placement');
        });
    }
};
