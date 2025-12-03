<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorneoParticipacion extends Model
{
    use HasFactory;

    protected $table = 'torneo_participaciones'; // Explicitly define table name for pivot model

    protected $fillable = [
        'torneo_id',
        'equipo_id',
        'proyecto_id',
        'fecha_inscripcion',
        'estado',
        'puntaje_total',
        'posicion',
        'premio_ganado',
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
        'puntaje_total' => 'integer',
        'posicion' => 'integer',
    ];

    // Relationships
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'torneo_participacion_id');
    }
}