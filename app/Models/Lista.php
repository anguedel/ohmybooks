<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista extends Model
{
    use HasFactory;

    protected $table = 'listas';

    protected $fillable = [
        'id_usuario',
        'nombre',
        
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function libros() /*De la relación N,N con la tabla Libro */
{
    return $this->belongsToMany(Libro::class, 'listas_libros', 'id_lista', 'id_libro');
}

}
