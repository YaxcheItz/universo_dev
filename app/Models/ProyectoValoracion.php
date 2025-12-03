<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProyectoValoracion extends Model
{
    use HasFactory;

    protected $table = 'proyecto_valoraciones';

    protected $fillable = [
        'proyecto_id',
        'user_id',
        'puntuacion',
        'comentario',
    ];

    protected $casts = [
        'puntuacion' => 'integer',
    ];

    // Relación con el proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    // Relación con el usuario que valoró
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
