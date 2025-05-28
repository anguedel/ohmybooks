<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Inertia\Inertia;

class InicioController extends Controller
{
    public function index()
    {
//Ordena la tabla de manera descendente por fecha de publicación, coge 12 y trae los atributos id, nombre y portada        
        $libros = Libro::orderBy('fecha_publicacion', 'desc') 
                       ->take(12)
                       ->get(['id', 'titulo', 'portada']);

//Renderiza la vista Inicio y le pasa un array de objetos con las propiedades indicadas arriba en el get
        return Inertia::render('Inicio', [
            'librosNovedades' => $libros,
        ]);
    }
}
