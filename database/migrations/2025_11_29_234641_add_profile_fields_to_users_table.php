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
            // Agregar columnas sin unique constraint primero
            if (!Schema::hasColumn('users', 'nickname')) {
                $table->string('nickname')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }
            if (!Schema::hasColumn('users', 'profile_bg_color')) {
                $table->string('profile_bg_color')->default('#1a1a2e');
            }
        });

        // Agregar índice único para nickname (SQLite lo acepta así)
        \DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS idx_users_nickname ON users(nickname) WHERE nickname IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nickname')) {
                $table->dropColumn('nickname');
            }
            if (Schema::hasColumn('users', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }
            if (Schema::hasColumn('users', 'profile_bg_color')) {
                $table->dropColumn('profile_bg_color');
            }
        });

        // Eliminar índice único
        \DB::statement('DROP INDEX IF EXISTS idx_users_nickname');
    }
};
