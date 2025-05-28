<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';
    

    protected $fillable = [
        'nombre',
        'nombre_usuario',
        'email',
        'password',
        'ciudad',
        'pais',
        'foto',
        'rol',
        'fecha_alta',
    ];

    protected $hidden = ['password'];

    public function likesFrases()
    {
        return $this->belongsToMany(FraseCelebre::class, 'likes_frases', 'usuario_id', 'frase_id')->withTimestamps();
    }

    // App\Models\User.php
    public function listas()
{
    return $this->hasMany(Lista::class, 'id_usuario');
}

public function valoraciones()
{
    return $this->hasMany(\App\Models\Valoracion::class, 'id_usuario');
}

public function comentarios()
{
    return $this->hasMany(\App\Models\Comentario::class, 'id_usuario');
}

public function libros()
{
    return $this->hasMany(\App\Models\Libro::class, 'id_usuario');
}

public function librosSugeridos()
{
    return $this->hasMany(\App\Models\LibroSugerido::class, 'id_usuario');
}






}
