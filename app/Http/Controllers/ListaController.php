<?php

namespace App\Http\Controllers;
use App\Models\Lista;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ListaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $listas = Lista::withCount('libros')
    ->get();


    return Inertia::render('ListasPredefinidasContenido', [
        'listas' => $listas
    ]);
}

public function agregarLibro(Request $request)
{
    Log::info('Petición recibida para añadir libro', $request->all());

    $request->validate([
        'id_lista' => 'required|exists:listas,id',
        'id_libro' => 'required|exists:libros,id',
    ]);

    // Verifica que la lista pertenece al usuario
    $lista = Lista::where('id', $request->id_lista)
        ->where('id_usuario', auth()->id())
        ->first();

    if (!$lista) {
        abort(403, 'No puedes modificar esta lista.');
    }

    // Inserta evitando duplicados
    $insertado = DB::table('listas_libros')->insertOrIgnore([
        'id_lista' => $request->id_lista,
        'id_libro' => $request->id_libro,
    ]);

    Log::info('Resultado insertOrIgnore:', ['insertado' => $insertado]);

    return back()->with('message', 'Libro añadido a la lista');
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
        'nombre' => 'required|string|max:255',
    ]);

    Lista::create([
        'id_usuario' => Auth::id(),
        'nombre' => $request->nombre,
    ]);

    return back()->with('message', 'Lista creada correctamente');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $lista = Lista::with('libros')->findOrFail($id);

    // Si necesitas añadir voto_usuario manualmente:
    $userId = auth()->id();

    foreach ($lista->libros as $libro) {
        $voto = \App\Models\Valoracion::where('id_libro', $libro->id)
            ->where('id_usuario', $userId)
            ->first();

        $libro->voto_usuario = $voto?->puntuacion;
    }

    return Inertia::render('ListasUsuarioContenido', [
        'lista' => $lista // 👈 esto debe existir tal cual
    ]);
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
    $request->validate([
        'nombre' => 'required|string|max:255',
    ]);

    $lista = Lista::where('id', $id)
        ->where('id_usuario', auth()->id())
        ->firstOrFail();

    $lista->nombre = $request->nombre;
    $lista->save();

    return back()->with('message', 'Lista actualizada correctamente');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $lista = Lista::where('id', $id)
        ->where('id_usuario', auth()->id())
        ->firstOrFail();

    $lista->delete();

    return back()->with('message', 'Lista eliminada correctamente');
}

}
