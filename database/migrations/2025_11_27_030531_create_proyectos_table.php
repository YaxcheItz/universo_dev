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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            
            // Información básica
                        $table->string('name');
            $table->text('descripcion');
            
            // Relaciones
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Creador del proyecto
            $table->foreignId('equipo_id')->nullable()->constrained('equipos')->onDelete('set null');
            
            // Tecnología
            $table->enum('lenguaje_principal', [
                'JavaScript', 'Python', 'Java', 'PHP', 'C#', 'C++', 
                'Ruby', 'Go', 'Swift', 'Kotlin', 'TypeScript', 'Rust'
            ]);
            $table->json('tecnologias')->nullable(); // Array de tecnologías adicionales
            
            // Estado y fechas
            $table->enum('estado', [
                'Planificación', 
                'En Desarrollo', 
                'Pruebas', 
                'Producción', 
                'Mantenimiento', 
                'Archivado'
            ])->default('Planificación');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->date('fecha_fin_real')->nullable();
            
            // URLs
            $table->string('repositorio_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('documentacion_url')->nullable();
            
            // Estadísticas
            $table->integer('estrellas')->default(0);
            $table->integer('forks')->default(0);
            $table->integer('contribuidores')->default(1);
            $table->integer('commits')->default(0);
            
            // Configuración
            $table->boolean('es_publico')->default(true);
            $table->boolean('es_trending')->default(false);
            
            // Timestamps y soft deletes
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para mejorar rendimiento
            $table->index('user_id');
            $table->index('empresa_id');
            $table->index('equipo_id');
            $table->index('lenguaje_principal');
            $table->index('estado');
            $table->index('es_trending');
            $table->index('es_publico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};