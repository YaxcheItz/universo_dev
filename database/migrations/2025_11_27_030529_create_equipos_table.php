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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('descripcion')->nullable();
            $table->foreignId('lider_id')->constrained('users')->cascadeOnDelete();
            $table->string('avatar')->nullable();
            $table->integer('max_miembros')->default(5);
            $table->integer('miembros_actuales')->default(1);
            $table->json('tecnologias')->nullable();
            $table->string('estado')->default('Activo');
            $table->boolean('es_publico')->default(true);
            $table->boolean('acepta_miembros')->default(true);
            $table->integer('proyectos_completados')->default(0);
            $table->integer('torneos_participados')->default(0);
            $table->integer('torneos_ganados')->default(0);
            $table->date('fecha_creacion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};