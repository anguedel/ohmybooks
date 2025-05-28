<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;


class Escrito extends Model
{
    use HasFactory;

    protected $table = 'escritos_usuarios';

    protected $fillable = [
        'usuario_id',
        'titulo',
        'contenido',
    ];  

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    

}
