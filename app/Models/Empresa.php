<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'razon_social',
        'rfc',
        'email',
        'telefono',
        'sitio_web',
        'descripcion',
        'industria',
        'direccion',
        'ciudad',
        'estado',
        'pais',
        'logo',
        'numero_empleados',
        'anio_fundacion',
    ];

    protected $casts = [
        'anio_fundacion' => 'integer',
        'numero_empleados' => 'integer',
    ];

    // Relaciones
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class);
    }
}