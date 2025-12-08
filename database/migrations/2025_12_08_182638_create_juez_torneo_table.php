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
        Schema::create('juez_torneo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torneo_id')->constrained('torneos')->onDelete('cascade');
            $table->foreignId('juez_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamps();

            // Un juez solo puede estar asignado una vez al mismo torneo
            $table->unique(['torneo_id', 'juez_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juez_torneo');
    }
};

