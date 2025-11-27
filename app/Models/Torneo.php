<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Torneo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'user_id',
        'categoria',
        'dominio',
        'fecha_inicio',
        'fecha_fin',
        'fecha_registro_inicio',
        'fecha_registro_fin',
        'tamano_equipo_min',
        'tamano_equipo_max',
        'max_participantes',
        'nivel_dificultad',
        'criterios_evaluacion',
        'premios',
        'reglas',
        'requisitos',
        'banner',
        'estado',
        'es_publico',
        'participantes_actuales',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_registro_inicio' => 'date',
        'fecha_registro_fin' => 'date',
        'criterios_evaluacion' => 'array',
        'premios' => 'array',
        'es_publico' => 'boolean',
        'tamano_equipo_min' => 'integer',
        'tamano_equipo_max' => 'integer',
        'max_participantes' => 'integer',
        'participantes_actuales' => 'integer',
    ];

    // ==================== RELACIONES ====================

    /**
     * Organizador del torneo
     */
    public function organizador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Participaciones en el torneo
     */
    public function participaciones()
    {
        return $this->hasMany(TorneoParticipacion::class);
    }

    /**
     * Equipos participantes
     */
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'torneo_participaciones')
            ->withPivot('fecha_inscripcion', 'estado', 'puntaje_total', 'posicion', 'premio_ganado')
            ->withTimestamps();
    }

    // ==================== SCOPES ====================

    /**
     * Torneos activos (inscripciones abiertas o en curso)
     */
    public function scopeActivos($query)
    {
        return $query->whereIn('estado', ['Inscripciones Abiertas', 'En Curso']);
    }

    /**
     * Torneos próximos
     */
    public function scopeProximos($query)
    {
        return $query->where('estado', 'Próximo');
    }

    /**
     * Torneos finalizados
     */
    public function scopeFinalizados($query)
    {
        return $query->where('estado', 'Finalizado');
    }

    /**
     * Torneos públicos
     */
    public function scopePublicos($query)
    {
        return $query->where('es_publico', true);
    }

    /**
     * Torneos por categoría
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Torneos por nivel de dificultad
     */
    public function scopePorNivel($query, $nivel)
    {
        return $query->where('nivel_dificultad', $nivel);
    }
}