<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Valoracion;
use App\Models\Lista;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $libros = Libro::with('autor', 'generos')->paginate(10); // o ->get() si no quieres paginación

        return Inertia::render('Admin/Libros/ListaLibros', [
            'libros' => $libros
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
    $request->validate([
        'titulo' => 'required|string|max:255',
        'sinopsis' => 'nullable|string',
        'autor' => 'nullable|string|max:255',
        'paginas' => 'nullable|integer',
        'editorial' => 'nullable|string|max:255',
        'genero_id' => 'required|exists:generos,id'
    ]);

    \App\Models\Libro::create([
        'titulo' => $request->titulo,
        'sinopsis' => $request->sinopsis,
        'autor' => $request->autor,
        'paginas' => $request->paginas,
        'editorial' => $request->editorial,
        'id_genero' => $request->genero_id
    ]);

    return redirect()->back();
}


    /**
     * Display the specified resource.
     */
    // LibroController.php
    public function show($id)
{
    // Obtener el libro con comentarios y sus usuarios
    $libro = Libro::with('comentarios.usuario')->findOrFail($id);

    // Por defecto no hay voto
    $votoUsuario = null;

    // Si el usuario está autenticado, buscamos su voto para este libro
    if (Auth::check()) {
        $votoUsuario = Valoracion::where('id_libro', $id)
            ->where('id_usuario', Auth::id())
            ->value('puntuacion');
    }

    // 📊 Cálculo de media y total de votos del libro
    $media = Valoracion::where('id_libro', $id)->avg('puntuacion');
    $totalVotos = Valoracion::where('id_libro', $id)->count();

    // Listas del usuario autenticado (para añadir a una lista)
    $listas = Auth::check()
        ? Lista::where('id_usuario', Auth::id())->get()
        : collect(); // colección vacía si no está logueado

    return Inertia::render('Libro', [
        'libro' => $libro,
        'votoUsuario' => $votoUsuario,
        'mediaVoto' => round($media, 1),
        'totalVotos' => $totalVotos,
        'listas' => $listas,
    ]);
}

    


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $libro = Libro::findOrFail($id);

        return Inertia::render('Fichalibro', [
            'libro' => $libro
        ]);
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
