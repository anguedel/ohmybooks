<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraseCelebre extends Model
{
    use HasFactory;

    protected $table = 'frases_celebres';

    protected $fillable = [
        'usuario_id',
        'texto',
        'autor',
        'categoria'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function usuariosQueDieronLike()
{
    return $this->belongsToMany(Usuario::class, 'likes_frases', 'frase_id', 'usuario_id')->withTimestamps();
}

}
