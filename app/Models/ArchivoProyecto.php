<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchivoProyecto extends Model
{
    protected $table = 'archivos_proyecto';

    protected $fillable = ['proyecto_id','user_id','filename','path','mime','size','status','comentario'];

    public function proyecto() { return $this->belongsTo(Proyecto::class); }
    public function user() { return $this->belongsTo(User::class); }
}
