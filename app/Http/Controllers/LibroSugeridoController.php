<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Models\LibroSugerido;

class LibroSugeridoController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ]);

    LibroSugerido::create([
        'id_usuario' => auth()->id(),
        'titulo' => $request->input('titulo'),
        'descripcion' => $request->input('descripcion'),
    ]);

    return ;
}

public function destroy($id)
{
    $sugerencia = LibroSugerido::findOrFail($id);
    $sugerencia->delete();

    return ;
}



}
