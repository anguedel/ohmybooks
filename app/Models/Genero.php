<?php

namespace App\Models;
use App\Models\Genero;
use Inertia\Inertia;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use HasFactory;

    protected $table = 'generos';

    protected $fillable = ['genero'];

    public function libros()
    {
        return $this->hasMany(Libro::class, 'id_genero');
    }
}


