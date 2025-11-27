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
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Organizador
            $table->string('categoria');
            $table->string('dominio');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->dateTime('fecha_registro_inicio');
            $table->dateTime('fecha_registro_fin');
            $table->integer('tamano_equipo_min')->default(1);
            $table->integer('tamano_equipo_max')->default(5);
            $table->integer('max_participantes')->nullable();
            $table->string('nivel_dificultad');
            $table->json('criterios_evaluacion');
            $table->json('premios');
            $table->text('reglas')->nullable();
            $table->text('requisitos')->nullable();
            $table->string('banner')->nullable();
            $table->string('estado')->default('Próximo');
            $table->boolean('es_publico')->default(true);
            $table->integer('participantes_actuales')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('torneos');
    }
};
