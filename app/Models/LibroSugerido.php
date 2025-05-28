<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibroSugerido extends Model
{
    use HasFactory;

    protected $table = 'libros_sugeridos';

    protected $fillable = ['id_usuario', 'titulo', 'descripcion'];
}


?>