<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        'id_libro' => 'required|exists:libros,id',
        'comentario' => 'required|string|max:1000',
    ]);

    Comentario::create([
        'id_libro' => $request->id_libro,
        'id_usuario' => Auth::id(),
        'comentario' => $request->comentario,
    ]);

    return back()->with('message', 'Comentario enviado');
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
    public function update(Request $request, $id)
{
    $comentario = Comentario::findOrFail($id);

    // Solo el autor puede editarlo (opcional, pero recomendable)
    if ($comentario->id_usuario !== Auth::id()) {
        abort(403, 'No autorizado');
    }

    $request->validate([
        'comentario' => 'required|string|max:1000',
    ]);

    $comentario->update([
        'comentario' => $request->comentario,
    ]);

    return back()->with('message', 'Comentario actualizado con éxito');
}

public function destroy($id)
{
    $comentario = Comentario::findOrFail($id);

    // Solo el autor puede eliminarlo
    if ($comentario->id_usuario !== Auth::id()) {
        abort(403, 'No autorizado');
    }

    $comentario->delete();

    return back()->with('message', 'Comentario eliminado con éxito');
}
}
