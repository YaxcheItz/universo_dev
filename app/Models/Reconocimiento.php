<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reconocimiento extends Model
{
    use HasFactory;

    protected $table = 'reconocimientos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'categoria',
        'rareza',
        'puntos',
        'requisitos',
    ];

    protected $casts = [
        'requisitos' => 'array',
        'puntos' => 'integer',
    ];

    // Relaciones
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'reconocimiento_user')
            ->withPivot('fecha_obtencion', 'nota')
            ->withTimestamps();
    }
}