<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ListasUsuarioController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $listas = $usuario->listas()
            ->withCount([
                'libros as libros_count' => function ($query) {
                    // Aquí puedes aplicar filtros si quieres
                }
            ])
            ->get()
            ->map(function ($lista) {
                return [
                    'id' => $lista->id,
                    'nombre' => $lista->nombre,
                    'total' => $lista->libros_count ?? 0, // Por si acaso
                ];
            });

        return Inertia::render('ListasUsuario', [
            'listas' => $listas
        ]);
    }
}
