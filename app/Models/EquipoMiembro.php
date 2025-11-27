<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipoMiembro extends Model
{
    use HasFactory;

    protected $table = 'equipo_miembros'; // Explicitly define table name for pivot model

    protected $fillable = [
        'equipo_id',
        'user_id',
        'rol_equipo',
        'fecha_ingreso',
        'fecha_salida',
        'estado',
        'contribuciones',
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'fecha_salida' => 'datetime',
        'contribuciones' => 'integer',
    ];

    // Relationships
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}