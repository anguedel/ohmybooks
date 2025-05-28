<?php

namespace App\Http\Controllers;
use App\Models\Valoracion;
use Illuminate\Http\Request;

class ValoracionController extends Controller
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
    \Log::info('Votacion recibida', [
        'user' => auth()->id(),
        'libro' => $request->id_libro,
        'puntuacion' => $request->puntuacion,
        

    ]);

    $request->validate([
        'id_libro' => 'required|exists:libros,id',
        'puntuacion' => 'required|integer|min:1|max:10',
    ]);

    try {
        $valoracion = \App\Models\Valoracion::where('id_usuario', auth()->id())
            ->where('id_libro', $request->id_libro)
            ->first();

        if ($valoracion) {
            $valoracion->puntuacion = $request->puntuacion;
            $valoracion->save();
            \Log::info('Valoración ACTUALIZADA', $valoracion->toArray());
        } else {
            $valoracion = \App\Models\Valoracion::create([
                'id_usuario' => auth()->id(),
                'id_libro' => $request->id_libro,
                'puntuacion' => $request->puntuacion,
                
            ]);
            \Log::info('Valoración CREADA', $valoracion->toArray());
        }

        return back()->with('success', 'Votación registrada correctamente.');
    } catch (\Exception $e) {
        \Log::error('Error al guardar votación', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
        ]);
        return back()->with('error', 'No se pudo registrar la votación.');
    }
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
