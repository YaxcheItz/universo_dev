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
        Schema::create('proyecto_valoraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('puntuacion')->unsigned()->comment('Puntuación de 1 a 5 estrellas');
            $table->text('comentario')->nullable();
            $table->timestamps();
            
            // Un usuario solo puede valorar un proyecto una vez
            $table->unique(['proyecto_id', 'user_id']);
            
            // Índices para mejorar rendimiento
            $table->index('proyecto_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto_valoraciones');
    }
};
