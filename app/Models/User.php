<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'password',
        'telefono',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'pais',
        'rol',
        'biografia',
        'avatar',
        'nickname',
        'profile_bg_color',
        'github_username',
        'linkedin_url',
        'portfolio_url',
        'habilidades',
        'puntos_total',
        'proyectos_completados',
        'torneos_ganados',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'habilidades' => 'array',
        'puntos_total' => 'integer',
        'proyectos_completados' => 'integer',
        'torneos_ganados' => 'integer',
    ];

    // ==================== RELACIONES ====================

    /**
     * Proyectos creados por el usuario
     */
    public function proyectosCreados()
    {
        return $this->hasMany(Proyecto::class, 'user_id');
    }

    /**
     * Archivos subidos por el usuario
     */
    public function archivosSubidos() 
    { 
        return $this->hasMany(ArchivoProyecto::class, 'user_id'); 
    }


    /**
     * Equipos donde el usuario es líder
     */
    public function equiposLiderados()
    {
        return $this->hasMany(Equipo::class, 'lider_id');
    }
    public function esLiderDeAlguno(): bool
    {
        return $this->equiposLiderados()->exists();
    }

    /**
     * Equipos a los que pertenece el usuario (como miembro)
     */
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_miembros')
            ->withPivot('rol_equipo', 'fecha_ingreso', 'fecha_salida', 'estado', 'contribuciones')
            ->withTimestamps();
    }

    /**
     * Torneos organizados por el usuario
     */
    public function torneosOrganizados()
    {
        return $this->hasMany(Torneo::class, 'user_id');
    }

    /**
     * Reconocimientos obtenidos por el usuario
     */
    public function reconocimientos()
    {
        return $this->belongsToMany(Reconocimiento::class, 'reconocimiento_user')
            ->withPivot('fecha_obtencion', 'nota')
            ->withTimestamps();
    }


    /**
     * Obtener el nombre completo del usuario
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->name} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    /**
     * Obtener las iniciales del usuario para el avatar
     */
    public function getInicialesAttribute()
    {
        $nombre = substr($this->name, 0, 1);
        $apellido = substr($this->apellido_paterno, 0, 1);
        return strtoupper($nombre . $apellido);
    }

    /**
     * Usuarios por rol
     */
    public function scopePorRol($query, $rol)
    {
        return $query->where('rol', $rol);
    }

    /**
     * Usuarios activos (no eliminados)
     */
    public function scopeActivos($query)
    {
        return $query->whereNull('deleted_at');
    }
    /**
     * Verificar si el usuario es juez
     */
    public function esJuez()
    {
        return $this->rol === 'Juez';
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdministrador()
    {
        return $this->rol === 'Administrador';
    }
    
}