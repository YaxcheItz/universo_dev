<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudMiembro extends Model
{
    use HasFactory;
    protected $table = 'solicitudes_miembros'; 

    protected $fillable = ['equipo_id','user_id','rol_equipo','estado'];

    public function equipo() {
        return $this->belongsTo(Equipo::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}


