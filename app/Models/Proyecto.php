<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proyecto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'descripcion',
        'empresa_id',
        'user_id',
        'equipo_id',
        'lenguaje_principal',
        'tecnologias',
        'estado',
        'fecha_inicio',
        'fecha_fin_estimada',
        'fecha_fin_real',
        'repositorio_url',
        'demo_url',
        'documentacion_url',
        'estrellas',
        'forks',
        'contribuidores',
        'commits',
        'es_publico',
        'es_trending',
    ];

    protected $casts = [
        'tecnologias' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin_estimada' => 'date',
        'fecha_fin_real' => 'date',
        'es_publico' => 'boolean',
        'es_trending' => 'boolean',
        'estrellas' => 'integer',
        'forks' => 'integer',
        'contribuidores' => 'integer',
        'commits' => 'integer',
    ];

    // ==================== RELACIONES ====================

    /**
     * Empresa asociada al proyecto
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Archivos asociados al proyecto
     */
    public function archivos() 
    { 
        return $this->hasMany(ArchivoProyecto::class, 'proyecto_id'); 
    }

    /**
     * Usuario creador del proyecto
     */
    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Equipo del proyecto
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    /**
     * Participaciones en torneos con este proyecto
     */
    public function torneoParticipaciones()
    {
        return $this->hasMany(TorneoParticipacion::class);
    }

    // ==================== SCOPES ====================

    /**
     * Proyectos trending (destacados)
     */
    public function scopeTrending($query)
    {
        return $query->where('es_trending', true);
    }

    /**
     * Proyectos públicos
     */
    public function scopePublico($query)
    {
        return $query->where('es_publico', true);
    }

    /**
     * Proyectos por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Proyectos por lenguaje
     */
    public function scopePorLenguaje($query, $lenguaje)
    {
        return $query->where('lenguaje_principal', $lenguaje);
    }

    /**
     * Proyectos más populares
     */
    public function scopePopulares($query)
    {
        return $query->orderBy('estrellas', 'desc');
    }

    /**
     * Proyectos recientes
     */
    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ==================== ACCESSORS ====================

    /**
     * Obtener el nombre del creador
     */
    public function getNombreCreadorAttribute()
    {
        return $this->creador ? $this->creador->nombre_completo : 'Desconocido';
    }

    /**
     * Verificar si el proyecto está activo
     */
    public function getEstaActivoAttribute()
    {
        return in_array($this->estado, ['En Desarrollo', 'Pruebas', 'Producción']);
    }

    /**
     * Verificar si un usuario es el líder del proyecto
     */
    public function esLider(User $user)
    {
        return $this->equipo && $this->equipo->esLider($user);
    }
}