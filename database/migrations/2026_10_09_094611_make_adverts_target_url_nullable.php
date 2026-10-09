<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Only needed where 2026_10_08_100000_create_adverts_table has already
 * run with a required target_url. On a fresh database the create
 * migration already makes the column nullable and this is a no-op change.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->string('target_url', 2048)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->string('target_url', 2048)->nullable(false)->change();
        });
    }
};
