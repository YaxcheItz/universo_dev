<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reconocimientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->string('icono'); // Nombre del icono (ej: trophy, star, code, etc.)
            $table->string('color'); // Color del badge
            $table->enum('categoria', ['Torneo', 'Proyecto', 'Equipo', 'Colaboración', 'Comunidad', 'Especial']);
            $table->enum('rareza', ['Común', 'Raro', 'Épico', 'Legendario'])->default('Común');
            $table->integer('puntos')->default(0); // Puntos que otorga
            $table->json('requisitos')->nullable(); // Condiciones para obtenerlo
            $table->timestamps();
        });

        // Tabla pivote para la relación muchos a muchos entre users y reconocimientos
        Schema::create('reconocimiento_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reconocimiento_id')->constrained('reconocimientos')->onDelete('cascade');
            $table->timestamp('fecha_obtencion');
            $table->text('nota')->nullable(); // Contexto de cómo se obtuvo
            $table->timestamps();
            
            // Índice único para evitar duplicados
            $table->unique(['user_id', 'reconocimiento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reconocimiento_user');
        Schema::dropIfExists('reconocimientos');
    }
};