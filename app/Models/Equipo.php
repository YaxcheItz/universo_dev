<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'lider_id',
        'avatar',
        'max_miembros',
        'miembros_actuales',
        'tecnologias',
        'estado',
        'es_publico',
        'acepta_miembros',
        'proyectos_completados',
        'torneos_participados',
        'torneos_ganados',
        'fecha_creacion',
    ];

    protected $casts = [
        'tecnologias' => 'array',
        'fecha_creacion' => 'date',
        'es_publico' => 'boolean',
        'acepta_miembros' => 'boolean',
        'max_miembros' => 'integer',
        'miembros_actuales' => 'integer',
        'proyectos_completados' => 'integer',
        'torneos_participados' => 'integer',
        'torneos_ganados' => 'integer',
    ];


    /**
     * Líder del equipo
     */
    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_id');
    }

    /**
     * Miembros del equipo
     */
    public function miembros()
    {
        return $this->belongsToMany(User::class, 'equipo_miembros')
            ->withPivot('rol_equipo', 'fecha_ingreso', 'fecha_salida', 'estado', 'contribuciones')
            ->withTimestamps();
    }

    /**
     * Proyectos del equipo
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }

    /**
     * Participaciones en torneos
     */
    public function torneoParticipaciones()
    {
        return $this->hasMany(TorneoParticipacion::class);
    }

    /**
     * Torneos en los que participa
     */
    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'torneo_participaciones')
            ->withPivot('fecha_inscripcion', 'estado', 'puntaje_total', 'posicion')
            ->withTimestamps();
    }

    // scopes
    /**
     * Equipos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'Activo');
    }

    /**
     * Equipos públicos
     */
    public function scopePublicos($query)
    {
        return $query->where('es_publico', true);
    }

    /**
     * Equipos que aceptan nuevos miembros
     */
    public function scopeAceptanMiembros($query)
    {
        return $query->where('acepta_miembros', true)
            ->whereColumn('miembros_actuales', '<', 'max_miembros');
    }
}