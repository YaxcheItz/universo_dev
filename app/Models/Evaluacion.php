<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'torneo_participacion_id',
        'juez_id',
        'calificaciones',
        'puntaje_total',
        'comentarios',
    ];

    protected $casts = [
        'calificaciones' => 'array',
        'puntaje_total' => 'decimal:2',
    ];

    /**
     * La participación que se está evaluando
     */
    public function participacion()
    {
        return $this->belongsTo(TorneoParticipacion::class, 'torneo_participacion_id');
    }

    /**
     * Alias para participacion (para compatibilidad)
     */
    public function torneoParticipacion()
    {
        return $this->belongsTo(TorneoParticipacion::class, 'torneo_participacion_id');
    }

    /**
     * El juez que realizó la evaluación
     */
    public function juez()
    {
        return $this->belongsTo(User::class, 'juez_id');
    }
}
