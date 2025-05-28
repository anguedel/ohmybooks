<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'titulo_original',
        'autor',
        'genero',
        'paginas',
        'fecha_publicacion',
        'sinopsis',
        'portada',
        'valoracion',
        'total_votos'
    ];
    

    public function autores() /*De la relación N,N con la tabla Libros */
{
    return $this->belongsToMany(Autor::class, 'autores_libros', 'id_libro', 'id_autor');
}

public function generos() /*De la relación N,N con la tabla Autores */
{
    return $this->belongsToMany(Genero::class, 'generos_libros', 'id_libro', 'id_genero');
}

public function listas() /*De la relación N,N con la tabla Listas    */
{
    return $this->belongsToMany(Lista::class, 'listas_libros', 'id_libro', 'id_lista');
}

public function comentarios()
{
    return $this->hasMany(Comentario::class, 'id_libro');
}





}
