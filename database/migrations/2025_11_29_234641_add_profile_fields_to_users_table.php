<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregar columnas
            if (!Schema::hasColumn('users', 'nickname')) {
                $table->string('nickname')->nullable()->unique();
            }
            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_bg_color')) {
                $table->string('profile_bg_color')->default('#1a1a2e');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nickname')) {
                $table->dropUnique(['nickname']); // Elimina el índice primero
                $table->dropColumn('nickname');
            }
            if (Schema::hasColumn('users', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }
            if (Schema::hasColumn('users', 'profile_bg_color')) {
                $table->dropColumn('profile_bg_color');
            }
        });
    }
};