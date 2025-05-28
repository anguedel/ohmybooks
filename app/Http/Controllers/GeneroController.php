<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genero;
use Inertia\Inertia;

class GeneroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function librosPorGenero()
{
    $generos = Genero::with('libros:id,id_genero,titulo,portada') // si 'titulo' y 'portada' existen en libros
        ->select('id', 'genero') // 👈 aquí está la clave: usa los campos reales de la tabla generos
        ->get();

        return Inertia::render('Generos', [
            'generos' => $generos
        ]);
        
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
