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
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torneo_participacion_id')->constrained('torneo_participaciones')->cascadeOnDelete();
            $table->foreignId('juez_id')->constrained('users')->cascadeOnDelete(); // Usuario con rol de Juez
            $table->json('calificaciones'); // Almacena las calificaciones por cada criterio
            $table->decimal('puntaje_total', 5, 2); // Puntaje total calculado
            $table->text('comentarios')->nullable();
            $table->timestamps();

            // Un juez solo puede evaluar una vez cada participación
            $table->unique(['torneo_participacion_id', 'juez_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
