<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escrito;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EscritoUsuarioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
        ]);

        Escrito::create([
            'usuario_id' => auth()->id(),
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
        ]);

        return back();
    }

    public function update(Request $request, Escrito $escrito)
{
    if ($escrito->usuario_id !== Auth::id()) {
        abort(403); // Prohibido
    }

    $request->validate([
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
    ]);

    $escrito->update([
        'titulo' => $request->titulo,
        'contenido' => $request->contenido,
    ]);

    return back();
}

public function destroy(Escrito $escrito)
{
    if ($escrito->usuario_id !== Auth::id()) {
        abort(403);
    }

    $escrito->delete();

    return back();
}

public function adminIndex()
{
    

    $escritos = Escrito::with('usuario:id,nombre_usuario')
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($escrito) {
            return [
                'id' => $escrito->id,
                'titulo' => $escrito->titulo,
                'autor' => $escrito->usuario->nombre_usuario ?? 'N/D',
                'fecha' => $escrito->created_at->format('Y-m-d'),
            ];
        });

    return Inertia::render('Admin/GestionEscritos', [
        'escritos' => $escritos,
    ]);
    dd($escritos);
}

public function panelAdmin()
{
    $escritos = Escrito::with('usuario:id,nombre_usuario')
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($escrito) {
            return [
                'id' => $escrito->id,
                'titulo' => $escrito->titulo,
                'autor' => $escrito->usuario->nombre_usuario ?? 'N/D',
                'fecha' => $escrito->created_at->format('Y-m-d'),
            ];
        });

    return Inertia::render('PanelControlAdmin', [
        'escritos' => $escritos,
    ]);
}

    
}
