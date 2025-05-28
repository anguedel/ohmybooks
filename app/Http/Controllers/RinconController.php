<?php

namespace App\Http\Controllers;

use App\Models\Escrito; 
use Inertia\Inertia;

class RinconController extends Controller
{
    public function index()
    {
        $escritos = Escrito::with('usuario:id,nombre,foto') // Se trae de la tabla Usuarios el id y el nombre
        ->latest()
        ->take(20)
        ->get();


        return Inertia::render('RinconEscritor', [
            'escritos' => $escritos
        ]);
    }
}

