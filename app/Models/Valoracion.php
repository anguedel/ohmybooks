<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    use HasFactory;

    protected $table = 'valoraciones';

    protected $fillable = ['id_usuario', 'id_libro', 'puntuacion'];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }
}
