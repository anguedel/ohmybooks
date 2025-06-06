<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';

    protected $fillable = ['nombre'];


    public function libros()
{
    return $this->belongsToMany(Libro::class, 'autores_libros', 'id_autor', 'id_libro');
}

}


